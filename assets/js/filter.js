const leagueSelect = document.querySelector('select#league');
const seasonSelect = document.querySelector('select#season');
const roundSelect = document.querySelector('select#round');

const initFilters = function () {

  if (!leagueSelect) {
    return;
  }

  const urlParams = new URLSearchParams(window.location.search);
  const season = urlParams.get('season');
  roundSelect.selected = true;
  for (let i = 0; i < seasonSelect.options.length; i++) {
    if (season === seasonSelect.options[i].value) {
      seasonSelect.options[i].selected = true;
    }
  }


  leagueSelect.addEventListener('change', function (event) {
    roundSelect.disabled = true;
    const seasons = event.target.options[event.target.selectedIndex].dataset.seasons;
    const obj = JSON.parse(seasons);

    const years = [];
    for (let i = 0, len = obj.length; i < len; ++i) {
      years.push(obj[i].year);
    }

    for (let i = 0; i < seasonSelect.options.length; i++) {
      const current = seasonSelect.options[i];
      current.style.display = 'block';
      if (!years.includes(parseInt(current.value))) {
        current.style.display = 'none';
      }
    }
  });

  seasonSelect.addEventListener('change', function (event) {
    roundSelect.disabled = true;
  });

};

document.addEventListener('DOMContentLoaded', function () {
  initFilters();
});