class PostElement extends HTMLElement {
  constructor() {
    super();

    // Attach the Shadow DOM
    const shadowRoot = this.attachShadow({ mode: 'open' });
    // Create a template for the custom element's content
    const template = document.createElement('template');
    template.innerHTML = `
      <style>
      :host{
        display: flex;
        flex-direction: column;
      }
      a {
        color: #1E90FF;
        text-decoration: none;
      }
      .content a {
        color: black;
      }
        .post_item {
          display: flex;
          flex-direction: column;
          max-width: 600px;
          padding: 10px;
          margin-bottom: 10px;
          border-radius: 17px;
          background-color: #06060617;
          width: fit-content;
        }
        
        .post_item_right {
          align-self: flex-end;
        }
        
        .post_item_right .post_user {
          display: flex;
          flex-direction: row-reverse;
          align-items: center;
          transform: translate(32px, -24px);
        }
        
        .post_user strong {
          margin-left: 16px;
          margin-right: 16px;
        }
        
        .post_item_right p {
          align-self: flex-end;
        }
        
        .post_item img {
          max-height: 400px;
          object-fit: contain;
          align-self: center;
        }
        
        .post_item p {
          margin-top: -20px;
        }
        
        .post_user {
          display: flex;
          flex-direction: row;
          align-items: center;
          transform: translate(-28px, -24px);
        }
        
        .post_user img {
          max-height: 60px;
          border-radius: 50%;
          border: 2px;
          border-style: ridge;
        }
        @media only screen and (max-width: 650px) {
          /* Modify styles to be more mobile-friendly */
        
        
          .user_panel {
              flex-direction: column; /* Stack user panels vertically on mobile */
              align-items: center; /* Center items on mobile */
          }
        
          .post_item_right .post_user {
              transform: none; /* Reset translation for mobile view */
              margin-top: 10px; /* Add some margin to separate post user info */
          }
          .post_item p{
            margin-top:0px;
          }
        
          .post_user {
              transform: none; /* Reset translation for mobile view */
              margin-top: 10px; /* Add some margin to separate post user info */
          }
        }
      </style>
      <div class="post_item">
        <!-- Your custom element's HTML markup here -->
      </div>
    `;

    // Clone the template content and attach it to the Shadow DOM
    shadowRoot.appendChild(template.content.cloneNode(true));
  }


  // Define your custom element's methods and behavior here
  set post(item) {
    
    const postDiv = this.shadowRoot.querySelector('.post_item');
    let toRight = false;
    if(item.sessionUser == item.username){
      toRight = true;
    }
    let embedFile = '';
    if (item.embed_file !== undefined && item.embed_file !== null && item.embed_file !== '') {
      embedFile = `<img src="${item.embed_file}" alt="Embedded File">`;
    }

    postDiv.innerHTML = `
      <div class="post_user">
        
          <img src="${item.avatar}" alt="User Avatar">
          <a href="profile.php?user=${item.username}"><strong>${item.nickname || item.username}</strong></a>
        
      </div>
      
        <p class='content'><a href="post.php?post=${item.id}">${item.content}</a></p>
        <a href="post.php?post=${item.id}">${embedFile}</a>
      
    `;
    if (toRight) { // Check if 'toRight' attribute exists and is not empty
      postDiv.classList.add('post_item_right');
    }
  }
  set toRight(value) {
    if (value) {
      this.setAttribute('to-right', 'true');
    } else {
      this.removeAttribute('to-right');
    }
  }

  // Define a getter for the 'toRight' attribute
  get toRight() {
    return this.hasAttribute('to-right');
  }
}

customElements.define('post-element', PostElement);
