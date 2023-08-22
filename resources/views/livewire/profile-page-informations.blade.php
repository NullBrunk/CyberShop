<div class="more-infos">
    <h5>My general informations</h5>
    <div class="space">
        <p class="general-rating">
            <i class="bi bi-star-fill"></i>
            <span><b>Average rating I gave : </b> {{ $rating }}</span>
        </p>

        <p class="number-of-like-under-comments">
            <i class="bi bi-chat-left-heart"></i>
            <span><b>Likes under my comments : </b> {{ $like_comments }}</span>
        </p>
    </div>

    <div class="space">
        <p class="number-of-products">
            <i class="bi bi-cart4"></i>
            <span><b>Products that I sale : </b> {{ $sail_product }}</span>
        </p>

        <p class="number-of-comments">
            <i class="bi bi-chat-left-text"></i>
            <span><b>Number of comments I posted : </b> {{ $comment_posted }}</span>
            
        </p>
    </div>
</div>