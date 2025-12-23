<style>
  body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    margin-top: 0px;
    padding: 20px;
    background: #fff;
    color: #000;
  }

  .section {
    max-width: 800px;
    margin: auto;
    margin-top: 50px;
  }

  .label {
    display: flex;
    align-items: center;
    font-size: 12px;
    color: #322EFF;
    font-weight: bold;
    margin-bottom: 10px;
    font-family: inherit;
  }

  .label::before {
    content: '';
    width: 6px;
    height: 18px;
    background-color: #322EFF;
    border-radius: 3px;
    margin-right: 6px;
  }

  .heading {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 20px;
  }

  .content {
    font-size: 16px; /* 🔽 Made text smaller */
    line-height: 1.6;
    color:white;
    font-weight: 500;
    transition: opacity 0.3s ease;
  }

  .controls {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
  }

  .arrow-btn {
    background: #f0f0f0;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    font-size: 18px;
    cursor: pointer;
    transition: background 0.2s ease;
  }

  .arrow-btn:hover {
    background: #ddd;
  }

  @media (max-width: 600px) {
    .content {
      font-size: 15px; /* 🔽 Also smaller on mobile */
    }

    .arrow-btn {
      width: 32px;
      height: 32px;
      font-size: 16px;
    }
  }
</style>

<div class="section">
  <div class="label">Stay Connected</div>
  <div class="heading">What is eVuba Connect</div>
  <div class="content" id="evuba-text">
    eVubaConnect saves you invaluable time streamlining processes that once took days into just minutes. Even first-time users can navigate the system effortlessly, enabling your team to focus on what truly matters: delivering exceptional service and growing your business.
  </div>

  <div class="controls">
    <button class="arrow-btn" onclick="prevText()">←</button>
    <button class="arrow-btn" onclick="nextText()">→</button>
  </div>
</div>

<script>
  const content = document.getElementById("evuba-text");

  const textSlides = [
    `eVubaConnect helps you streamline workflows that once consumed days, down to just minutes. The system is intuitive, letting even new users get started quickly and stay efficient with minimal training.`,

    `With eVubaConnect, teams collaborate better across departments, reducing delays and mistakes. Dashboards offer real-time updates so decisions can be made faster and with greater confidence.`,

    `Whether you're scaling or just starting, eVubaConnect molds to your needs. Its smart automation removes manual steps, helping your team stay focused, agile, and driven toward results.`,

    `Security is built into eVubaConnect from the ground up. Your data is protected through encryption and access control, so only authorized users can view sensitive or private information.`
  ];

  let currentIndex = 0;

  function updateContent(index) {
    content.style.opacity = 0;
    setTimeout(() => {
      content.textContent = textSlides[index];
      content.style.opacity = 1;
    }, 200);
  }

  function nextText() {
    currentIndex = (currentIndex + 1) % textSlides.length;
    updateContent(currentIndex);
  }

  function prevText() {
    currentIndex = (currentIndex - 1 + textSlides.length) % textSlides.length;
    updateContent(currentIndex);
  }
</script>
