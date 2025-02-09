<script src="./plugin/Jquery/jquery-3.7.1.slim.min.js"></script>
<script src="./plugin/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
<script src="./plugin/DataTables/datatables.js"></script>
<script src="./plugin/sweetalert/sweetalert2@11.js"></script>
<script src="./plugin/select2/select2.min.js"></script>
<script src="./plugin/inputmask/imask.js"></script>
<script src="./src/js/script.js" type="module"></script>
<script>
document.addEventListener("DOMContentLoaded", function(event) {
    const showNavbar = (toggleId, navId, bodyId, headerId) =>{
        const toggle = document.getElementById(toggleId),
        nav = document.getElementById(navId),
        bodypd = document.getElementById(bodyId),
        headerpd = document.getElementById(headerId)

        // Validate that all variables exist
        if(toggle && nav && bodypd && headerpd){
            toggle.addEventListener('click', ()=>{
                // show navbar
                nav.classList.toggle('show-h')
                // change icon
                toggle.classList.toggle('bx-x')
                // add padding to body
                bodypd.classList.toggle('body-pd')
                // add padding to header
                headerpd.classList.toggle('body-pd')
            })
        }
    }

    showNavbar('header-toggle','nav-bar','body-pd','header')

    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link')

    function colorLink(){
        if(linkColor){
            linkColor.forEach(l=> l.classList.remove('active'))
            this.classList.add('active')
        }
    }
    linkColor.forEach(l=> l.addEventListener('click', colorLink))

    // Your code to run since DOM is loaded and ready
});
</script>