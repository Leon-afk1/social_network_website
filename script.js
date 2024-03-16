const trendingPosts = [
  {
    image: 'https://picsum.photos/id/1015/500/500',
    title: 'Post 1',
    author: 'Author 1',
    Comments: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam pulvinar ac metus at suscipit. Nunc sit amet tortor eleifend, malesuada ex eu, facilisis justo. Nulla facilisi. Aenean rhoncus dapibus risus, vel fermentum felis. Duis massa lorem, interdum et dui vel, sodales condimentum nibh. Vivamus vestibulum id justo quis dictum. Donec et sem ipsum. Suspendisse sed elit finibus erat consequat pretium a quis nibh. Curabitur et odio nec magna iaculis interdum at in dolor. Nullam quis nisi quis diam finibus convallis. Integer accumsan pulvinar erat ac pretium. Praesent ultricies leo eget turpis placerat elementum. Nullam et consectetur risus, quis feugiat urna',
  },
  {
    image: 'https://picsum.photos/id/1016/500/500',
    title: 'Post 2',
    author: 'Author 2',
    Comments: 'Comments 2',
  },
  {
    image: 'https://picsum.photos/id/1020/500/500',
    title: 'Post 3',
    author: 'Author 3',
    Comments: 'Comments 3',
  },
];

const followingPosts = [
  {
    image: 'https://picsum.photos/id/1018/500/500',
    title: 'Post 4',
    author: 'Author 4',
    Comments: 'Comments 4',
  },
  {
    image: 'https://picsum.photos/id/1019/500/500',
    title: 'Post 5',
    author: 'Author 5',
    Comments: 'Comments 5',
  },
  {
    image: 'https://picsum.photos/id/1020/500/500',
    title: 'Post 6',
    author: 'Author 6',
    Comments: 'Comments 6',
  },
];

const trendingPostsList = document.getElementById('trending-posts');
const followingPostsList = document.getElementById('following-posts');

trendingPosts.forEach((post) => {
  const li = document.createElement('li');
  const img = document.createElement('img');
  const title = document.createElement('h3');
  const author = document.createElement('p');
  const comments = document.createElement('p');

  img.src = post.image;
  title.textContent = post.title;
  author.textContent = post.author;
  comments.textContent = post.Comments;

  li.appendChild(img);
  li.appendChild(title);
  li.appendChild(author);
  li.appendChild(comments);

trendingPostsList.appendChild(li);
});

followingPosts.forEach((post) => {
  const li = document.createElement('li');
  const img = document.createElement('img');
  const title = document.createElement('h3');
  const author = document.createElement('p');

  img.src = post.image;
  title.textContent = post.title;
  author.textContent = post.author;

  li.appendChild(img);
  li.appendChild(title);
  li.appendChild(author);

  followingPostsList.appendChild(li);
});

const switchPageButton = document.getElementById('switch-page-button');

switchPageButton.addEventListener('click', () => {
  const currentPage = '<?php echo $page; ?>';
  const nextPage = currentPage === 'for-you' ? 'following' : 'for-you';
  window.location.href = `index.php?page=${nextPage}`;
});