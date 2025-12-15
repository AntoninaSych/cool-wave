<template>
  <div class="flex flex-wrap gap-4">
    <!-- Images -->
    <div
        v-for="(image, index) in previewImages"
        :key="image.key"
        class="relative w-32 h-32 border border-dashed rounded-lg flex items-center justify-center cursor-move"
        draggable="true"
        @dragstart="onDragStart(index)"
        @dragover.prevent
        @drop="onDrop(index)"
    >
      <img
          v-if="image.url"
          :src="image.url"
          class="w-full h-full object-cover rounded-lg"
          alt="Preview"
      />

      <button
          type="button"
          class="absolute top-1 right-1 bg-white rounded-full shadow p-1"
          @click="removeImage(image)"
      >
        ✕
      </button>
    </div>

    <!-- Upload -->
    <label
        class="w-32 h-32 border border-dashed rounded-lg flex items-center justify-center cursor-pointer text-gray-500 hover:text-gray-700"
    >
      Upload
      <input
          type="file"
          multiple
          accept="image/*"
          class="hidden"
          @change="onFileChange"
      />
    </label>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  images: {
    type: Array,
    required: true
  },
  deletedImages: {
    type: Array,
    required: true
  },
  imagePositions: {
    type: Object,
    required: true
  }
})

const emit = defineEmits([
  'update:images',
  'update:deleted-images',
  'update:image-positions'
])

const draggedIndex = ref(null)

/**
 * Resolve image URL:
 * - absolute URL -> use as is
 * - relative path -> prepend /storage
 */
function resolveImageUrl(path) {
  if (!path) return null

  if (/^https?:\/\//i.test(path)) {
    return path
  }

  const cleanPath = path.replace(/^\/?storage\//, '')
  return `${window.location.protocol}//${window.location.hostname}/storage/${cleanPath}`
}

/**
 * Normalize images for preview
 */
const previewImages = computed(() => {
  return props.images.map((image) => {
    // New uploaded file
    if (image instanceof File) {
      return {
        key: `${image.name}-${image.size}`,
        file: image,
        url: URL.createObjectURL(image)
      }
    }

    // Existing backend image
    const path =
        image.url ||
        image.path ||
        image.image ||
        null

    return {
      key: `image-${image.id}`,
      id: image.id,
      url: resolveImageUrl(path)
    }
  })
})

function onFileChange(event) {
  const files = Array.from(event.target.files || [])
  emit('update:images', [...props.images, ...files])
  event.target.value = ''
}

function removeImage(image) {
  const deleted = Array.isArray(props.deletedImages)
      ? props.deletedImages
      : []

  if (image.id) {
    emit('update:deleted-images', [...deleted, image.id])
  }

  emit(
      'update:images',
      props.images.filter((img) => {
        if (img instanceof File) {
          return img !== image.file
        }
        return img.id !== image.id
      })
  )
}

/**
 * Drag & Drop ordering
 */
function onDragStart(index) {
  draggedIndex.value = index
}

function onDrop(targetIndex) {
  if (draggedIndex.value === null) return

  const reordered = [...props.images]
  const [moved] = reordered.splice(draggedIndex.value, 1)
  reordered.splice(targetIndex, 0, moved)

  emit('update:images', reordered)

  const positions = {}
  reordered.forEach((img, index) => {
    if (!(img instanceof File) && img.id) {
      positions[img.id] = index
    }
  })

  emit('update:image-positions', positions)

  draggedIndex.value = null
}
</script>

<style scoped>
[draggable="true"]:active {
  opacity: 0.7;
}
</style>
