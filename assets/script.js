// Variables
const globalBtn = document.getElementById("global");
const localBtn = document.getElementById("local");
const searchBtn = document.getElementById("searchBtn");

const search = document.getElementById("search");
const category = document.getElementById("category");
const details = document.getElementById("details");

// Array
var newsData = [];

// APIs
const API_KEY = "98da51f500314fedb0539941248d5929";
const HEADLINES = "https://newsapi.org/v2/top-headlines?country=us&apiKey=";
const global_NEWS = "https://newsapi.org/v2/top-headlines?country=&category=general&apiKey=";
const local_NEWS = "https://newsapi.org/v2/top-headlines?country=ph&category=general&apiKey=";
const SEARCH_NEWS = "https://newsapi.org/v2/everything?q=";

window.onload = function() {
    category.innerHTML="<h4 class='text-info'>HEADLINES</h4>";
    fetchHeadlines();
};
globalBtn.addEventListener("click",function(){
    category.innerHTML="<h4 class='text-secondary'>GLOBAL</h4>";
    fetchGeneralNews();
});
localBtn.addEventListener("click",function(){
    category.innerHTML="<h4 class='text-secondary'>LOCAL</h4>";
    fetchBusinessNews();
});

searchBtn.addEventListener("click",function(){
    category.innerHTML="<h4 class='text-secondary'>Search : "+search.value+"</h4>";
    fetchQueryNews();
});
const fetchHeadlines = async () => {
    const response = await fetch(HEADLINES+API_KEY);
    newsData = [];
    if(response.status >=200 && response.status < 300) {
        const myJson = await response.json();
        newsData = myJson.articles;
    } else {
        // handle errors
        console.log(response.status, response.statusText);
        details.innerHTML = "<h5>No data found.</h5>"
        return;
    }
    displayNews();
}
const fetchGeneralNews = async () => {
    const response = await fetch(global_NEWS+API_KEY);
    newsData = [];
    if(response.status >=200 && response.status < 300) {
        const myJson = await response.json();
        newsData = myJson.articles;
    } else {
        // handle errors
        console.log(response.status, response.statusText);
        details.innerHTML = "<h5>No data found.</h5>"
        return;
    }
    displayNews();
}
const fetchBusinessNews = async () => {
    const response = await fetch(local_NEWS+API_KEY);
    newsData = [];
    if(response.status >=200 && response.status < 300) {
        const myJson = await response.json();
        newsData = myJson.articles;
    } else {
        // handle errors
        console.log(response.status, response.statusText);
        details.innerHTML = "<h5>No data found.</h5>"
        return;
    }
    displayNews();
}
const fetchQueryNews = async () => {
    if(search.value == null)
        return;
    const response = await fetch(SEARCH_NEWS+encodeURIComponent(search.value)+"&apiKey="+API_KEY);
    newsData = [];
    if(response.status >= 200 && response.status < 300) {
        const myJson = await response.json();
        newsData = myJson.articles;
    } else {
        //error handle
        console.log(response.status, response.statusText);
        details.innerHTML = "<h5>No data found.</h5>"
        return;
    }
    displayNews();
}
function displayNews() {
    details.innerHTML = "";
    // if(newsData.length == 0) {
    //     details.innerHTML = "<h5>No data found.</h5>"
    //     return;
    // }
    newsData.forEach(news => {
        var date = news.publishedAt.split("T");
        var col = document.createElement('div');
        col.className="col-sm-12 col-md-4 col-lg-3 p-2 card";
        var card = document.createElement('div');
        card.className = "p-2";
        var image = document.createElement('img');
        image.setAttribute("height","matchparent");
        image.setAttribute("width","100%");
        image.src=news.urlToImage;
        var cardBody = document.createElement('div');
        var newsHeading = document.createElement('h5');
        newsHeading.className = "card-title";
        newsHeading.innerHTML = news.title;
        var dateHeading = document.createElement('h6');
        dateHeading.className = "text-primary";
        dateHeading.innerHTML = date[0];
        var discription = document.createElement('p');
        discription.className="text-muted";
        discription.innerHTML = news.description;
        var link = document.createElement('a');
        link.className="btn btn-dark";
        link.setAttribute("target", "_blank");
        link.href = news.url;
        link.innerHTML="Read more";
        cardBody.appendChild(newsHeading);
        cardBody.appendChild(dateHeading);
        cardBody.appendChild(discription);
        cardBody.appendChild(link);
        card.appendChild(image);
        card.appendChild(cardBody);
        col.appendChild(card);
        details.appendChild(col);
    });
}