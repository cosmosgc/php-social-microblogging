// ItemElement.js
class ItemElement extends HTMLElement {
    constructor() {
      super();
  
      // Attach the Shadow DOM
      const shadowRoot = this.attachShadow({ mode: 'open' });
  
      // Get the attributes for icon and description
      const iconSrc = this.getAttribute('icon') || '';
      const description = this.getAttribute('description') || '';
  
      // Create the item element markup
      const template = document.createElement('template');
      template.innerHTML = `
        <style>
            img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
          }
          
          p {
            margin: 0;
            font-size: 16px;
          }
        </style>
        <div class="itemElement">
          <img src="${iconSrc}" alt="Item Icon">
          <p>${description}</p>
        </div>
      `;
  
      // Clone the template content and attach it to the Shadow DOM
      shadowRoot.appendChild(template.content.cloneNode(true));
    }
  }
  
  // Define the custom element
  customElements.define('item-element', ItemElement);
  