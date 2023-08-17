// JavaScript using Fetch to get microblogs data from API
let pageNumber = 1;
const itemsPerPage = 10;
let lastPostId = 0; // Store the last post ID received from the server
const apiURL = `posts.php`;
let pageParam = `?page=${pageNumber}`;
const postsContainer = document.getElementById('postsContainer');
const loadMoreButton = document.getElementById('loadMore');

async function getMicroblogsData() {
    const response = await fetch(apiURL+pageParam);
    const data = await response.json();
    for (let i = postsContainer.children.length - 1; i > 0; i--) {
        const child = postsContainer.children[i];
        postsContainer.removeChild(child);
    }
    lastPostId = data[0].id;
    console.log(lastPostId);
    if (data.length > 0) {
        data.forEach(item => {
            if(item.post_theme !=0){
                //let postDiv = buildPostElementWithTheme(item, item.post_theme);
                //postsContainer.innerHTML += postDiv;
                let postDiv = buildPostElement(item);
                postsContainer.appendChild(postDiv);
            }else{
                let postDiv = buildPostElement(item);
                postsContainer.appendChild(postDiv);
            }
        });

        // If there are more posts, enable the load more button
        if (data.length >= itemsPerPage) {
            pageNumber++;
            loadMoreButton.style.display = 'block';
        } else {
            loadMoreButton.style.display = 'none';
        }
    } else {
        loadMoreButton.style.display = 'none';
    }
}
async function getMicroblogsDataAppend() {
    pageParam = `?page=${pageNumber++}`;
    const response = await fetch(apiURL+pageParam);
    const data = await response.json();
    
    if (data.length > 0) {
        data.forEach(item => {
            
            if(item.post_theme !=0){
                //let postDiv = buildPostElementWithTheme(item, item.post_theme);
                //postsContainer.innerHTML += postDiv;
                let postDiv = buildPostElement(item);
                postsContainer.appendChild(postDiv);
            }else{
                const postDiv = buildPostElement(item);
                postsContainer.appendChild(postDiv);
            }
            const postDiv = buildPostElement(item);
            
            postsContainer.appendChild(postDiv);
        });

        // If there are more posts, enable the load more button
        if (data.length >= itemsPerPage) {
            pageNumber++;
            loadMoreButton.style.display = 'block';
        } else {
            loadMoreButton.style.display = 'none';
        }
    } else {
        loadMoreButton.style.display = 'none';
    }
}
function buildPostElement(item) {

    // Create an instance of the post-element custom element
    const postElement = document.createElement('post-element');
    Object.assign(item, {sessionUser: phpUsername});
    postElement.post = item;
    if(item.username == phpUsername){
        postElement.toRight = true;
    }
    return postElement;
}
function buildPostElementWithTheme(item, theme){
    let displayName = item.nickname ? item.nickname : item.username;
    let toRight = false
    let postDiv = '';
    if(item.username == phpUsername){
        toRight = true;
    }
    theme = theme*1;
    switch(theme) {
        case 1:
            who = 'us';
            if(toRight)
                who = 'you';
            postDiv = `<div class="${who}" style="--icon: url(${item.avatar})"><name>${displayName}</name>
            ${item.content}</div>`;
          break;
          case 2:
            postDiv = `<div class="underticon" style='--icon: url(${item.avatar})'><div>${item.content}</div></div>`
          break;
          case 3:
            postDiv = `<div class="splat2">
            <h1>${displayName}</h1>
            <img src="${item.avatar}" alt="" class="">
            <div>${item.content}</div>
            </div>`
          break;
        default:
            postDiv = 'sem tema';
      }
    return postDiv;
}
loadMoreButton.addEventListener('click', () => {
    console.log(pageNumber);
    //apiURL = `posts.php?page=${pageNumber}`;
    getMicroblogsDataAppend();
});

//getMicroblogsData(); // Initial call to load the first set of posts

// JavaScript to handle the modal popup
const newPostButton = document.getElementById('newPostButton');
const modal = document.getElementById('postModal');
const modalClose = document.getElementById('modalClose');

// Open the modal popup
newPostButton.addEventListener('click', () => {
    modal.style.display = 'block';
});

// Close the modal popup
modalClose.addEventListener('click', () => {
    modal.style.display = 'none';
});

// Close the modal when clicking outside of it
window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
// JavaScript using AJAX for form submission
const postForm = document.getElementById('postForm');
const messageDiv = document.getElementById('message');

postForm.addEventListener('submit', (event) => {
    event.preventDefault();
    modal.style.display = 'none';
    const formData = new FormData(postForm);

    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Display the success or error message
        //messageDiv.textContent = data.message;
        //messageDiv.style.display = 'block';

        // Clear the form fields if the submission was successful
        if (data.status === 'success') {
            postForm.reset();
            getMicroblogsData();
        }
    })
    .catch(error => {
        // Display an error message if the fetch request failed
        //messageDiv.textContent = 'Error occurred while submitting the form.';
        //messageDiv.style.display = 'block';
    });
});
// JavaScript using Fetch to check for new posts
const newPostsButton = document.getElementById('newPostsButton');


function checkForNewPosts() {
    fetch(`check_new_posts.php?lastPostId=${lastPostId}`)
        .then(response => response.json())
        .then(data => {
            if (data.newPosts) {
                // New posts are available, show the button
                //newPostsButton.style.display = 'block';
            } else {
                // No new posts, hide the button
                newPostsButton.style.display = 'none';
                console.log("Sem novas mensagens");
            }
        })
        .catch(error => {
            console.error('Error checking for new posts:', error);
        });
}

// Function to reload the page with updated posts
function reloadWithNewPosts() {
    getMicroblogsData();
}

// Check for new posts every 10 seconds (adjust the interval as needed)
setInterval(checkForNewPosts, 10000);

// Attach click event to the new posts button
newPostsButton.addEventListener('click', reloadWithNewPosts);
