// Mock data for Movies
const Movies = [
    {
        id: 1,
        name: "The Shawshank Redemption",
        date: "1994-09-22",
    },
    {
        id: 2,
        name: "The Godfather",
        date: "1972-03-24",
    },
    {
        id: 3,
        name: "Pulp Fiction",
        date: "1994-10-14",
    }
];

// Function to display Movies on the Movies page
function displayMovies() {
    const MovieList = document.querySelector('.Movie-list');
    if (MovieList) {
        MovieList.innerHTML = ''; // Clear existing content
        Movies.forEach(Movie => {
            const MovieDiv = document.createElement('div');
            MovieDiv.className = 'Movie';
            MovieDiv.innerHTML = `
                <h3>${Movie.name}</h3>
                <p>Date: ${Movie.date}</p>
            `;
            MovieList.appendChild(MovieDiv);
        });
    }
}

// Initialize the site
function init() {
    displayMovies();
}

window.onload = init;