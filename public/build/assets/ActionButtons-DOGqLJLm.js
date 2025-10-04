import{S as p,A as w,E as f}from"./save-DpFoz-B6.js";import{d as x,C as s,K as C,e as l,o as n,F as _,r as k,B as A,L as B,f as y,i as I,h as L,n as o}from"./app-CZIMIw-m.js";const c=`<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M11.5 22C6.26 22 2 17.75 2 12.5A9.5 9.5 0 0 1 11.5 3a9.5 9.5 0 0 1 9.5 9.5a9.5 9.5 0 0 1-9.5 9.5m0-1a8.5 8.5 0 0 0 8.5-8.5c0-2.17-.81-4.15-2.14-5.65l-12.01 12A8.468 8.468 0 0 0 11.5 21m0-17A8.5 8.5 0 0 0 3 12.5c0 2.17.81 4.14 2.15 5.64l12-12A8.49 8.49 0 0 0 11.5 4Z"/></svg>
`,E=`<svg
    xmlns="http://www.w3.org/2000/svg"
    width="18"
    height="18"
    viewBox="0 0 24 24"
>
    <path
        fill="none"
        stroke="currentColor"
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="1.5"
        d="m20 9l-1.995 11.346A2 2 0 0 1 16.035 22h-8.07a2 2 0 0 1-1.97-1.654L4 9m17-3h-5.625M3 6h5.625m0 0V4a2 2 0 0 1 2-2h2.75a2 2 0 0 1 2 2v2m-6.75 0h6.75"
    />
</svg>
`,M=["innerHTML"],i="flex justify-end gap-2",V=x({__name:"ActionButtons",props:{class:{default:i},mode:{default:"view"},buttons:{default:()=>({view:[{icon:E,class:"btn-action bg-red-500 hover:bg-red-600 text-white",label:"Del",action:"remove",disabled:!1},{icon:f,class:"btn-action bg-emerald-600 hover:bg-emerald-700 text-white",label:"Edit",action:"edit",disabled:!1}],add:[{icon:c,class:"btn-action bg-emerald-600 hover:bg-emerald-700 text-white",label:"Cancel",action:"cancel",disabled:!1},{icon:w,class:"btn-action bg-blue-500 hover:bg-blue-600 text-white",label:"Add",action:"add",disabled:!1}],edit:[{icon:c,class:"btn-action bg-rose-500 hover:bg-rose-600 text-white",label:"Cancel",action:"cancel",disabled:!1},{icon:p,class:"btn-action bg-yellow-500  text-gray-800 hover:bg-yellow-400 hover:text-gray-700",label:"Save",action:"save",disabled:!1}]})}},setup(d,{emit:r}){const a=d,b=s(()=>a.class===""?i:a.class),h=r,m=s(()=>{const t=a.mode;return a.buttons[t]}),v=t=>{h(t)};return(t,S)=>{const g=C("base-button");return n(),l("div",{class:o(b.value)},[(n(!0),l(_,null,k(m.value,(e,u)=>(n(),A(g,{key:u,class:o(e.class),disabled:e.disabled,onClick:D=>v(e.action)},{default:B(()=>[y("span",{class:"pr-[4px]",innerHTML:e.icon},null,8,M),I(" "+L(e.label),1)]),_:2},1032,["class","disabled","onClick"]))),128))],2)}}});export{V as default};
