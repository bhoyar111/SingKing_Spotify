const scriptURL = 'https://script.google.com/macros/s/AKfycbyEsuqnHRAoHWxL4QesdPMiyIYdEcKPpwaDXmpVFkyye2cUTfZ8OTGqPxoXn5c7yjZJ/exec'
const form = document.forms['google-sheet']

form.addEventListener('submit', e => {
    e.preventDefault()
    fetch(scriptURL, { method: 'POST', body: new FormData(form)})
    .then(response => {
        form.reset();
        document.getElementById("openModelBtnId").click();
    })
    .catch(error => console.error('Error!', error.message))
});

//Get the button
// var mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
// window.onscroll = function () { scrollFunction() };

// function scrollFunction() {
//     if (document.body.scrollTop > 1 || document.documentElement.scrollTop > 20) {
//         mybutton.fadeIn().removeClass('d-none');
//     } else {
//         mybutton.fadeOut().addClass('d-none'); 
//     }
// }

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function fillRequestForm(itemObj){
    document.getElementById("spotifyId").value = itemObj.id; 
    document.getElementById("albumNameId").value = itemObj.name;
    document.getElementById("artistNameId").value = itemObj.name; 
    document.getElementById("arTitleId").value = itemObj.type; 
    document.getElementById("divSubmitRequestId").scrollIntoView();
}

function showListing(){
    document.getElementById("albumListSectionId").scrollIntoView();
}

// document.getElementById("divFirst").scrollIntoView();
// For Offcanvar Sidebar

(function () {
    'use strict'
    document.querySelector('#navbarSideCollapse').addEventListener('click', function () {
        document.querySelector('.offcanvas-collapse').classList.toggle('open')
    })
})()