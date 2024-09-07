" Vim plugin - Numbered tabs
fu! TabLabel(n)

let buflist = tabpagebuflist(a:n)
let winnr = tabpagewinnr(a:n)
let string = fnamemodify(bufname(buflist[winnr - 1]), ':t')

return empty(string) ? '[unnamed]' : string
endfu

fu! ChangedStatus(n)
let flag = 0
let status = '*'
let buflist = tabpagebuflist(a:n)
let winnr = tabpagewinnr(a:n)
let flag = getbufvar(bufname(buflist[winnr - 1]), '&mod')

if flag == 0
let status = ' '
endif

return status
endfu

fu! NumberingTabs()
let tabline = ''

" create tabline in loop
for i in range(tabpagenr('$'))

	" highlight current tab
	if i+1 == tabpagenr()
	let tabline .= '%#TabLineSel#'
	else
	let tabline .= '%#TabLineFill#'
	endif

    " display tabnumber (for use with <count>gt, etc)
    let tabline .= ' '.(i+1)

    " the label is made by TabLabel()
    let tabline .= '%{ChangedStatus('.(i+1).')}'.'%{TabLabel('.(i+1).')} '
endfor

return tabline
endfu

set tabline=%!NumberingTabs()
