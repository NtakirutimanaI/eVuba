<section id="contact" class="evc-contact-section">
  <div class="evc-contact-wrapper">

    <!-- Left Image -->
    <div class="evc-contact-image">
      <img src="{{ asset('images/contact.png') }}" alt="Contact Image">
    </div>

    <!-- Right Form -->
    <div class="evc-contact-form">
      <h2>Send us a message</h2>
      
      <!-- Success Message -->
      <div id="evc-success-msg" style="display:none; padding:10px; margin-bottom:15px; border-radius:6px; background:#d4edda; color:#155724;"></div>

      <form id="evc-contact-form" action="{{ route('contact.send') }}" method="POST">
        @csrf
        <div class="evc-form-group evc-name-group">
          <label for="first_name">Name <span>*</span></label>
          <input type="text" id="first_name" name="first_name" placeholder="First" required>
          <input type="text" id="last_name" name="last_name" placeholder="Last" required>
        </div>

        <div class="evc-form-group">
          <label for="email">Email <span>*</span></label>
          <input type="email" id="email" name="email" placeholder="Email" required>
        </div>

        <div class="evc-form-group">
          <label for="message">Type your message here <span>*</span></label>
          <textarea id="message" name="message" rows="5" placeholder="Your message..." required></textarea>
        </div>

        <button type="submit" class="evc-submit-btn">SUBMIT</button>
      </form>
    </div>

  </div>
</section>

<style>
/* ================= CONTACT SECTION ================= */
.evc-contact-section {
  padding: 60px 20px;
  background: linear-gradient(135deg, #e0edff, #ffffff);
  display: flex;
  justify-content: center;
  align-items: center;
}

.evc-contact-wrapper {
  display: flex;
  max-width: 900px;
  width: 100%;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  background: #fff;
  transition: transform 0.3s ease;
}

.evc-contact-wrapper:hover {
  transform: translateY(-5px);
}

.evc-contact-image {
  flex: 1;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #d6e5ff;
}

.evc-contact-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.evc-contact-image img:hover {
  transform: scale(1.05);
}

.evc-contact-form {
  flex: 1;
  padding: 40px 30px;
  background: linear-gradient(145deg, #ffffff, #f0f4ff);
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.evc-contact-form h2 {
  font-size: 26px;
  margin-bottom: 25px;
  font-weight: bold;
  color: #0A1128;
  text-align: center;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

.evc-form-group {
  margin-bottom: 20px;
  display: flex;
  flex-direction: column;
}

.evc-name-group {
  flex-direction: row;
  gap: 10px;
}

.evc-form-group label {
  margin-bottom: 6px;
  font-weight: 600;
  color: #0A1128;
}

.evc-form-group label span {
  color: red;
}

.evc-form-group input,
.evc-form-group textarea {
  padding: 12px 15px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 14px;
  width: 100%;
  background: #f9faff;
  box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
  transition: all 0.3s ease;
}

.evc-form-group input:focus,
.evc-form-group textarea:focus {
  border-color: #007bff;
  box-shadow: 0 0 8px rgba(0,123,255,0.2);
  outline: none;
}

.evc-name-group input {
  flex: 1;
}

.evc-form-group textarea {
  resize: none;
}

.evc-submit-btn {
  padding: 12px 20px;
  background: linear-gradient(135deg, #007bff, #0056b3);
  color: #fff;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
}

.evc-submit-btn:hover {
  background: linear-gradient(135deg, #0056b3, #003d80);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

@media (max-width: 900px) {
  .evc-contact-wrapper {
    flex-direction: column;
    width: 90%;
  }

  .evc-contact-image {
    height: 200px;
  }

  .evc-contact-form {
    padding: 25px 20px;
  }

  .evc-name-group {
    flex-direction: column;
  }

  .evc-contact-form h2 {
    font-size: 22px;
  }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    $('#evc-contact-form').submit(function(e){
        e.preventDefault(); // Prevent default form submission

        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.post(url, data)
        .done(function(response){
            $('#evc-success-msg').text(response.success).fadeIn();
            form[0].reset();
        })
        .fail(function(xhr){
            let errors = xhr.responseJSON.errors;
            let errorText = '';
            $.each(errors, function(key, value){
                errorText += value + ' ';
            });
            $('#evc-success-msg').text(errorText).css('background','#f8d7da').css('color','#721c24').fadeIn();
        });
    });

});
</script>
