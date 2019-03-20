from fabric.api import local

def deploy():

    local("git add . && git commit -m 'Fix'" )
    local("git push")