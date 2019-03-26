from fabric.api import *
env.hosts = ['add_ip_or_domain']
env.user = 'root'
env.password = 'blah!'

def push():
    local("git add . && git commit -m 'Fix'" )
    local("git push")

def deploy():
     code_dir = '/srv/django/myproject'
     with cd(code_dir):
         run("git pull")
         run("php artisan migrate:fresh --seed")

def update():
    push()
    deploy()
