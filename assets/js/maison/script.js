const searchBtn = document.querySelector('#search-submit');

searchBtn.addEventListener('click', event => {
    document.querySelector('form[name=search]').submit();
});
