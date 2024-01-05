const changebtn = document.getElementById('changebtn');
const savebtn = document.getElementById('savebtn');
const resetbtn = document.getElementById('resetbtn');
const cancelbtn = document.getElementById('cancelbtn');
const userInput = document.getElementById('userInput');
const oldusername = userInput.value;
const passInput = document.getElementById('passInput');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

changebtn.addEventListener('click', (e) => {
    userInput.disabled = false;
    passInput.disabled = false;
    passInput.setAttribute('type', 'text');
    savebtn.style.display = 'block';
    resetbtn.style.display = 'block';
    cancelbtn.style.display = 'block';
    e.target.style.display = 'none';
})

cancelbtn.addEventListener('click', (e) => {
    e.target.closest('form').reset();
    userInput.disabled = true;
    passInput.disabled = true;
    passInput.setAttribute('type', 'password');
    changebtn.style.display = 'block';
    savebtn.style.display = 'none';
    resetbtn.style.display = 'none';
    e.target.style.display = 'none';
})