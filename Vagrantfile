# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

  config.vm.box = "ubuntu/focal64"
  # MacOS default safe range of private network IPs: 192.168.56.0 - 192.168.63.255
  config.vm.network "private_network", ip: "192.168.56.10"
  config.vm.network "forwarded_port", guest: 80, host: 81, auto_correct: true
  config.vm.network "forwarded_port", guest: 3306, host: 3307, auto_correct: true
  config.vm.network "forwarded_port", guest: 1080, host: 1080, auto_correct: true
  config.vm.network "forwarded_port", guest: 8080, host: 8080, auto_correct: true

  config.vm.synced_folder ".", "/vagrant/", :mount_options=>["dmode=777,fmode=777"]

  # Provider-specific configuration
  config.vm.provider "virtualbox" do |vb|
    vb.name = "coolwave"
    vb.gui = false
    vb.memory = 2048
    vb.cpus = 1
  end

  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = "provision/ansible/playbook-local.yml"
    ansible.install_mode = "pip3"
    ansible_become = "yes"
    ansible_become_method = "su"
    ansible_become_exe = "sudo su -"
    # ansible.verbose = "vv"
    ansible.extra_vars = {
      foo_token: 'fd7829b82ee280abcc38',
      bar_password: 'frisbee11',
      azure_client_id: "",
      azure_client_secret: "",
      azure_tenant_id: "",
      azure_proxy: "",
      bar_password: ""
    }
  end

end
