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
      <div id="evc-success-msg"
        ></div>

      <form id="evc-contact-form" action="{{ route('contact.send') }}" method="POST">
        @csrf
        <div class="evc-form-row">
          <div class="evc-input-group">
            <input type="text" id="first_name" name="first_name" required>
            <label for="first_name">First Name <span>*</span></label>
            <div class="input-underline"></div>
          </div>
          
          <div class="evc-input-group">
            <input type="text" id="last_name" name="last_name" required>
            <label for="last_name">Last Name <span>*</span></label>
            <div class="input-underline"></div>
          </div>
        </div>

        <div class="evc-input-group">
          <input type="email" id="email" name="email" required>
          <label for="email">Email Address <span>*</span></label>
          <div class="input-underline"></div>
        </div>

        <div class="evc-input-group">
          <textarea id="message" name="message" rows="5" required></textarea>
          <label for="message">Your Message <span>*</span></label>
          <div class="input-underline"></div>
        </div>

        <button type="submit" class="evc-submit-btn">
          <span class="btn-text">SUBMIT</span>
          <span class="btn-icon">→</span>
        </button>
      </form>
    </div>

  </div>
</section>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

/* ================= CONTACT SECTION ================= */
.evc-contact-section {
  padding: 80px 20px;
  background: linear-gradient(135deg, 
    hsl(220, 100%, 95%) 0%, 
    hsl(260, 100%, 97%) 50%, 
    hsl(280, 100%, 98%) 100%);
  position: relative;
  overflow: hidden;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.evc-contact-section::before {
  content: '';
  position: absolute;
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.15), transparent 70%);
  top: -200px;
  right: -200px;
  border-radius: 50%;
  animation: float 8s ease-in-out infinite;
}

.evc-contact-section::after {
  content: '';
  position: absolute;
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(139, 92, 246, 0.12), transparent 70%);
  bottom: -150px;
  left: -150px;
  border-radius: 50%;
  animation: float 10s ease-in-out infinite reverse;
}

@keyframes float {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  50% { transform: translateY(-30px) rotate(5deg); }
}

.evc-contact-wrapper {
  display: flex;
  max-width: 1000px;
  width: 100%;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 
    0 20px 60px rgba(0, 0, 0, 0.12),
    0 0 0 1px rgba(255, 255, 255, 0.8) inset;
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(20px);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  z-index: 1;
}

.evc-contact-wrapper:hover {
  transform: translateY(-8px);
  box-shadow: 
    0 30px 80px rgba(0, 0, 0, 0.18),
    0 0 0 1px rgba(255, 255, 255, 0.9) inset;
}

.evc-contact-image {
  flex: 1;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, 
    hsl(230, 80%, 70%) 0%, 
    hsl(250, 90%, 65%) 100%);
  position: relative;
}

.evc-contact-image::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, 
    transparent 0%, 
    rgba(255, 255, 255, 0.1) 100%);
  pointer-events: none;
}

.evc-contact-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  filter: brightness(1.05) contrast(1.1);
}

.evc-contact-image img:hover {
  transform: scale(1.08);
  filter: brightness(1.1) contrast(1.15);
}

.evc-contact-form {
  flex: 1.2;
  padding: 50px 45px;
  background: linear-gradient(165deg, 
    rgba(255, 255, 255, 0.95) 0%, 
    rgba(248, 250, 255, 0.98) 100%);
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
}

.evc-contact-form::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, 
    hsl(230, 90%, 60%), 
    hsl(260, 90%, 65%), 
    hsl(280, 90%, 70%));
  opacity: 0.8;
}

.evc-contact-form h2 {
  font-size: 32px;
  margin-bottom: 35px;
  font-weight: 700;
  background: linear-gradient(135deg, 
    hsl(230, 70%, 30%), 
    hsl(250, 80%, 40%));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-align: left;
  letter-spacing: -0.5px;
  position: relative;
}

.evc-contact-form h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, 
    hsl(230, 90%, 60%), 
    hsl(260, 90%, 65%));
  border-radius: 2px;
}

.evc-form-row {
  display: flex;
  gap: 20px;
  margin-bottom: 8px;
}

.evc-input-group {
  position: relative;
  margin-bottom: 32px;
  flex: 1;
}

.evc-input-group input,
.evc-input-group textarea {
  width: 100%;
  padding: 16px 16px 12px;
  border: none;
  border-radius: 12px;
  font-size: 15px;
  font-family: 'Inter', sans-serif;
  background: rgba(255, 255, 255, 0.7);
  box-shadow: 
    0 4px 12px rgba(0, 0, 0, 0.06),
    0 0 0 1px rgba(99, 102, 241, 0.08) inset;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  color: hsl(230, 30%, 20%);
  backdrop-filter: blur(10px);
}

.evc-input-group textarea {
  resize: vertical;
  min-height: 120px;
  font-family: 'Inter', sans-serif;
}

.evc-input-group label {
  position: absolute;
  left: 16px;
  top: 16px;
  font-size: 15px;
  font-weight: 500;
  color: hsl(230, 20%, 50%);
  pointer-events: none;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  background: transparent;
  padding: 0 4px;
}

.evc-input-group label span {
  color: hsl(0, 70%, 55%);
  font-weight: 600;
}

.input-underline {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, 
    hsl(230, 90%, 60%), 
    hsl(260, 90%, 65%));
  transform: scaleX(0);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border-radius: 0 0 12px 12px;
}

.evc-input-group input:focus,
.evc-input-group textarea:focus {
  outline: none;
  background: rgba(255, 255, 255, 0.95);
  box-shadow: 
    0 8px 24px rgba(99, 102, 241, 0.15),
    0 0 0 2px rgba(99, 102, 241, 0.2) inset;
  transform: translateY(-2px);
}

.evc-input-group input:focus ~ .input-underline,
.evc-input-group textarea:focus ~ .input-underline {
  transform: scaleX(1);
}

.evc-input-group input:focus ~ label,
.evc-input-group input:not(:placeholder-shown) ~ label,
.evc-input-group textarea:focus ~ label,
.evc-input-group textarea:not(:placeholder-shown) ~ label {
  top: -10px;
  left: 12px;
  font-size: 12px;
  font-weight: 600;
  color: hsl(230, 90%, 55%);
  background: linear-gradient(to bottom, 
    transparent 0%, 
    transparent 40%, 
    rgba(248, 250, 255, 0.98) 40%, 
    rgba(248, 250, 255, 0.98) 100%);
}

.evc-submit-btn {
  padding: 16px 32px;
  background: linear-gradient(135deg, 
    hsl(230, 90%, 60%) 0%, 
    hsl(250, 85%, 62%) 50%, 
    hsl(270, 80%, 65%) 100%);
  color: #fff;
  font-weight: 600;
  font-size: 15px;
  letter-spacing: 1px;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  box-shadow: 
    0 8px 24px rgba(99, 102, 241, 0.35),
    0 0 0 1px rgba(255, 255, 255, 0.2) inset;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-top: 8px;
}

.evc-submit-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, 
    transparent, 
    rgba(255, 255, 255, 0.3), 
    transparent);
  transition: left 0.5s;
}

.evc-submit-btn:hover::before {
  left: 100%;
}

.evc-submit-btn:hover {
  transform: translateY(-3px);
  box-shadow: 
    0 12px 32px rgba(99, 102, 241, 0.45),
    0 0 0 1px rgba(255, 255, 255, 0.3) inset;
}

.evc-submit-btn:active {
  transform: translateY(-1px);
  box-shadow: 
    0 6px 16px rgba(99, 102, 241, 0.35),
    0 0 0 1px rgba(255, 255, 255, 0.2) inset;
}

.btn-icon {
  font-size: 18px;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.evc-submit-btn:hover .btn-icon {
  transform: translateX(4px);
}

#evc-success-msg {
  display: none;
  padding: 14px 18px;
  margin-bottom: 20px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 500;
  backdrop-filter: blur(10px);
  animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

#evc-success-msg.success {
  background: linear-gradient(135deg, 
    rgba(16, 185, 129, 0.15), 
    rgba(5, 150, 105, 0.1));
  color: hsl(160, 70%, 30%);
  border: 1px solid rgba(16, 185, 129, 0.3);
}

#evc-success-msg.error {
  background: linear-gradient(135deg, 
    rgba(239, 68, 68, 0.15), 
    rgba(220, 38, 38, 0.1));
  color: hsl(0, 70%, 40%);
  border: 1px solid rgba(239, 68, 68, 0.3);
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 900px) {
  .evc-contact-wrapper {
    flex-direction: column;
    width: 95%;
    border-radius: 20px;
  }

  .evc-contact-image {
    height: 220px;
    min-height: 220px;
  }

  .evc-contact-form {
    padding: 35px 25px;
  }

  .evc-contact-form h2 {
    font-size: 26px;
    text-align: center;
    margin-bottom: 30px;
  }

  .evc-contact-form h2::after {
    left: 50%;
    transform: translateX(-50%);
  }

  .evc-form-row {
    flex-direction: column;
    gap: 0;
  }

  .evc-input-group {
    margin-bottom: 28px;
  }
}

@media (max-width: 600px) {
  .evc-contact-section {
    padding: 50px 15px;
  }

  .evc-contact-form h2 {
    font-size: 24px;
  }

  .evc-submit-btn {
    width: 100%;
    font-size: 14px;
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
            $('#evc-success-msg')
                .removeClass('error')
                .addClass('success')
                .text(response.success)
                .fadeIn();
            form[0].reset();
            
            // Hide success message after 5 seconds
            setTimeout(function(){
                $('#evc-success-msg').fadeOut();
            }, 5000);
        })
        .fail(function(xhr){
            let errors = xhr.responseJSON.errors;
            let errorText = '';
            $.each(errors, function(key, value){
                errorText += value + ' ';
            });
            $('#evc-success-msg')
                .removeClass('success')
                .addClass('error')
                .text(errorText)
                .fadeIn();
            
            // Hide error message after 7 seconds
            setTimeout(function(){
                $('#evc-success-msg').fadeOut();
            }, 7000);
        });
    });

});
</script>
