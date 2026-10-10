(()=>{
  'use strict';
  const titles={dashboard:'控制台',orders:'订单',products:'产品上新与更新',messages:'咨询回复',articles:'Race Moment',customers:'客户信息'};
  const body=document.body;
  const links=[...document.querySelectorAll('[data-control-preview-link]')];
  const views=[...document.querySelectorAll('[data-control-view]')];
  const heading=document.querySelector('[data-control-title]');
  const menu=document.querySelector('[data-control-menu]');
  const collapse=document.querySelector('[data-control-collapse]');
  const nav=document.querySelector('[data-control-nav]');
  const toast=document.querySelector('[data-control-toast]');
  const setCollapsed=collapsed=>{body.classList.toggle('apx-control-collapsed',collapsed);collapse?.setAttribute('aria-expanded',String(!collapsed));if(collapse){const label=collapsed?'展开侧栏':'收起侧栏';collapse.setAttribute('aria-label',label);collapse.title=label}};
  const openView=name=>{const target=titles[name]?name:'dashboard';views.forEach(view=>view.hidden=view.dataset.controlView!==target);links.forEach(link=>link.classList.toggle('is-active',link.dataset.controlPreviewLink===target));heading.textContent=titles[target];document.title=`${titles[target]} — APEXIK Control`;nav.classList.remove('is-open');menu.setAttribute('aria-expanded','false');window.scrollTo(0,0)};
  let stored=false;
  try{stored=window.localStorage.getItem('apx_control_sidebar')==='collapsed'}catch(error){stored=false}
  setCollapsed(stored);
  collapse?.addEventListener('click',()=>{const next=!body.classList.contains('apx-control-collapsed');setCollapsed(next);try{window.localStorage.setItem('apx_control_sidebar',next?'collapsed':'expanded')}catch(error){}});
  links.forEach(link=>link.addEventListener('click',event=>{event.preventDefault();const name=link.dataset.controlPreviewLink;history.replaceState(null,'',`#${name}`);openView(name)}));
  menu.addEventListener('click',()=>{const open=!nav.classList.contains('is-open');nav.classList.toggle('is-open',open);menu.setAttribute('aria-expanded',String(open))});
  document.addEventListener('keydown',event=>{if(event.key==='Escape'){nav.classList.remove('is-open');menu.setAttribute('aria-expanded','false')}});
  document.querySelectorAll('form').forEach(form=>form.addEventListener('submit',event=>{event.preventDefault();if(!form.reportValidity())return;toast.hidden=false;toast.textContent='演示数据已保存；WordPress 安装版会写入真实数据。';window.scrollTo({top:0,behavior:'smooth'});setTimeout(()=>{toast.hidden=true},3200)}));
  openView(location.hash.slice(1)||'dashboard');
})();
