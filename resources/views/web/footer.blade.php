<footer class="evc-footer">
  <div class="evc-footer-column">
    <h3>eVubaConnect</h3>
    <p>Your trusted partner</p>
    <p>for enterprise IT solutions, connectivity, and hardware services.</p>

    <!-- Success message -->
    <div id="evc-footer-success" style="width:323px;display:none; padding:8px; margin-top:8px; border-radius:4px; background:#4ade80; color:#155724; font-size:13px;"></div>

    <form id="evc-footer-form" action="{{ route('subscribe.store') }}" method="POST" class="evc-footer-form">
      @csrf
      <input type="email" name="email" placeholder="Enter your email" required />
      <button type="submit">&gt;</button>
    </form>
  </div>

  <div class="evc-footer-column">
    <h3>Support</h3>
    <p>Rwanda, Kigali,<br>Gasabo-Gisozi</p>
    <p>evubaconnect@gmail.com</p>
    <p>+250 786 325 291</p>
  </div>

  <div class="evc-footer-column">
    <h3>Quick Link</h3>
    <a href="{{route('web.privacy-policy')}}">Privacy Policy</a>
    <a href="{{route('web.terms-of-use')}}">Terms Of Use</a>
    <a href="{{route('web.faq')}}">FAQ</a>
    <a href="{{route('web.contact')}}">Contact</a>
  </div>
</footer>

<div class="evc-footer-bottom">
  © 2025 VUBA TECH Ltd. All rights reserved.
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
/* ================= UNIQUE FOOTER ================= */
.evc-footer {
  background: #0A1128;
  color: #fff;
  padding: 40px 20px;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  font-family: Arial, sans-serif;
  margin-top: 30px;
}

.evc-footer-column {
  flex: 1 1 180px;
  margin: 10px;
}

.evc-footer h3 {
  font-size: 16px;
  margin-bottom: 10px;
}

.evc-footer p,
.evc-footer a {
  font-size: 14px;
  color: #ccc;
  text-decoration: none;
  display: block;
  margin-bottom: 8px;
}

.evc-footer-form input[type="email"] {
  padding: 8px;
  width: 70%;
  border: none;
  border-radius: 2px;
}

.evc-footer-form button {
  padding: 8px 12px;
  background: transparent;
  border: 1px solid #fff;
  color: #fff;
  cursor: pointer;
}

.evc-footer-success {
  color: #4ade80;
  font-size: 13px;
  margin-top: 8px;
}

.evc-footer-icons {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.evc-footer-icons a {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background-color: #222;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  color: #fff;
  font-size: 14px;
  transition: background 0.3s;
}

.evc-footer-icons a:hover {
  background-color: #322EFF;
}

.evc-footer-bottom {
  text-align: center;
  font-size: 12px;
  color: #888;
  padding: 10px 0;
  background-color: #0b0b0b;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
  .evc-footer {
    flex-direction: column;
    align-items: flex-start;
  }

  .evc-footer-icons {
    margin-bottom: 20px;
  }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    $('#evc-footer-form').submit(function(e){
        e.preventDefault(); // Prevent default form submission

        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.post(url, data)
        .done(function(response){
            $('#evc-footer-success').text(response.success).fadeIn();
            form[0].reset(); // Clear the email input
        })
        .fail(function(xhr){
            let errors = xhr.responseJSON.errors;
            let errorText = '';
            $.each(errors, function(key, value){
                errorText += value + ' ';
            });
            $('#evc-footer-success').text(errorText).css('background','#f8d7da').css('color','#721c24').fadeIn();
        });
    });

});
</script>
