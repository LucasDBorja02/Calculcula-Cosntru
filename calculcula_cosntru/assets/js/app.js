// CalculCula Cosntru - client helpers (v2)
(function(){
  const navLinks = document.querySelectorAll('[data-tab]');
  const tabs = document.querySelectorAll('.tab');

  function showTab(id){
    tabs.forEach(t => t.style.display = (t.id === id ? 'block' : 'none'));
    navLinks.forEach(a => a.classList.toggle('active', a.getAttribute('data-tab') === id));
    if (location.hash !== '#' + id) history.replaceState(null, '', '#' + id);
  }

  navLinks.forEach(a=>{
    a.addEventListener('click', (e)=>{
      e.preventDefault();
      showTab(a.getAttribute('data-tab'));
    });
  });

  // Load initial tab from hash OR server hint
  const hash = (location.hash || '').replace('#','').trim();
  const hinted = (window.__INITIAL_TAB__ || '').trim();
  const initial = (hash && document.getElementById(hash)) ? hash :
                  (hinted && document.getElementById(hinted)) ? hinted :
                  'inicio';
  showTab(initial);
})();
