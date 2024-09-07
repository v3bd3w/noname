## Auto entering into netns

TTY=`/usr/bin/tty`
if test "${TTY}" = "/dev/tty2"
then
	NETNS=`/usr/bin/ip netns`
	if test "${NETNS}" = "isolator"
	then
		/usr/sbin/ifconfig
	else
		/usr/bin/ip netns exec isolator /usr/bin/bash
		exit 0
	fi
fi

## allow Ctrl+s pressing
/bin/stty start ^- ; /bin/stty stop ^-

## Copy (remove) files named starting with dot
shopt -s dotglob

## set creation access mode -rw-r--r--  and drwxr-xr-x 
umask 022

## If not running interactively, don't do anything
case $- in
    *i*) ;;
      *) return;;
esac

## don't put duplicate lines or lines starting with space in the history.
HISTCONTROL=ignoreboth

## append to the history file, don't overwrite it
shopt -s histappend

## for setting history length see HISTSIZE and HISTFILESIZE in bash(1)
HISTSIZE=2048
HISTFILESIZE=8192

## check the window size after each command and, if necessary, update the values of LINES and COLUMNS.
shopt -s checkwinsize

## some more ls aliases
alias ssh='ssh -o ServerAliveInterval=5'
alias sftp='sftp -o ServerAliveInterval=5'
alias mc='mc -d'
alias dir='dir -a -b -1 --group-directories-first -p -l -h --time-style=iso'
alias date='/usr/bin/date +"%g-%m-%d_%H:%M"'
alias netns='/usr/bin/ip netns exec isolator /usr/bin/bash'
alias pkg-list="dpkg-query  -f='\${binary:Package} - \${binary:Synopsis}\n' -W"
alias su-bash="su -P -s /bin/sh -c bash -l"

export PS1="\n\u \h \w \n> "

