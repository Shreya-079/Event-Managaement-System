// Simple form validation for registration
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    if(form){
        form.addEventListener("submit", function(e){
            const email = document.querySelector("input[name='email']").value;
            if(!email.includes("@")){
                alert("Please enter a valid email!");
                e.preventDefault();
            }
        });
    }
});
