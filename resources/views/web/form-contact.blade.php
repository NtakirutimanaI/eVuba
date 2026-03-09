<div class="card border-0 shadow-lg rounded-4 overflow-hidden">
  <div class="card-body p-4 p-md-5">
    <h3 class="fw-bold mb-4 text-dark text-center text-lg-start">Send us a message</h3>

    <div id="evc-success-msg"></div>

    <form id="evc-contact-form" action="{{ route('contact.send') }}" method="POST">
      @csrf
      <div class="row g-4 mb-4">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control bg-light border-0" id="first_name" name="first_name"
              placeholder="First Name" required>
            <label for="first_name" class="text-muted">First Name <span class="text-danger">*</span></label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control bg-light border-0" id="last_name" name="last_name"
              placeholder="Last Name" required>
            <label for="last_name" class="text-muted">Last Name <span class="text-danger">*</span></label>
          </div>
        </div>
      </div>

      <div class="form-floating mb-4">
        <input type="email" class="form-control bg-light border-0" id="email" name="email" placeholder="Email Address"
          required>
        <label for="email" class="text-muted">Email Address <span class="text-danger">*</span></label>
      </div>

      <div class="form-floating mb-4">
        <textarea class="form-control bg-light border-0" id="message" name="message" placeholder="Your Message"
          style="height: 150px" required></textarea>
        <label for="message" class="text-muted">Your Message <span class="text-danger">*</span></label>
      </div>

      <button type="submit"
        class="btn btn-primary rounded-pill px-5 py-3 fw-bold w-100 shadow-sm d-flex align-items-center justify-content-center gap-2 transition-all"
        style="background-color: var(--primary-light); border-color: var(--primary-light);">
        <span>SUBMIT</span>
        <i class="fas fa-paper-plane" style="font-size: 0.9em;"></i>
      </button>
    </form>
  </div>
</div>

<style>
  #evc-success-msg {
    display: none;
    padding: 14px 18px;
    margin-bottom: 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
  }

  #evc-success-msg.success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #34d399;
  }

  #evc-success-msg.error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #f87171;
  }

  .form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
    background-color: white !important;
  }

  button[type="submit"]:hover {
    box-shadow: 0 12px 25px rgba(37, 99, 235, 0.3) !important;
    transform: translateY(-2px);
  }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $('#evc-contact-form').submit(function (e) {
      e.preventDefault();

      let form = $(this);
      let url = form.attr('action');
      let data = form.serialize();
      let btn = form.find('button[type="submit"]');
      let originalBtnText = btn.html();

      btn.html('<i class="fas fa-spinner fa-spin"></i> <span>Sending...</span>').prop('disabled', true);

      $.post(url, data)
        .done(function (response) {
          $('#evc-success-msg')
            .removeClass('error')
            .addClass('success')
            .text(response.success || 'Message sent successfully!')
            .fadeIn();
          form[0].reset();

          setTimeout(function () {
            $('#evc-success-msg').fadeOut();
          }, 5000);
        })
        .fail(function (xhr) {
          let errorText = 'An error occurred. Please try again.';
          if (xhr.responseJSON && xhr.responseJSON.errors) {
            errorText = '';
            $.each(xhr.responseJSON.errors, function (key, value) {
              errorText += value + ' ';
            });
          }
          $('#evc-success-msg')
            .removeClass('success')
            .addClass('error')
            .text(errorText)
            .fadeIn();

          setTimeout(function () {
            $('#evc-success-msg').fadeOut();
          }, 7000);
        })
        .always(function () {
          btn.html(originalBtnText).prop('disabled', false);
        });
    });
  });
</script>