const apikey = "c44d2b9613b2b14da8ed19b3590daec3";
const searchBox = document.getElementById("changeCity");
const searchBtn = document.getElementById("btn");

async function checkWeather(cityName) {
    
    try {
        let data;
        if (navigator.onLine) {
            const response = await fetch(`http://disanprototype2.infinityfreeapp.com/Prototype2/Disan_Yadav_2432716.php?q=${cityName}`);
            if (!response.ok) {
                throw new Error("City not found");
            }
            data = await response.json();
            // console.log(data);
            localStorage.setItem(cityName, JSON.stringify(data));
        } else {
            data = JSON.parse(localStorage.getItem(cityName));
            if (!data) {
                throw new Error("City data not found in localStorage.");
            }
        }
        console.log(data);
        document.getElementById("cityN").innerHTML = data[0].city;
        document.getElementById("weather-condition").innerHTML = data[0].weather_condition;
        
        document.getElementById("temp").innerHTML = data[0].temp;
        document.getElementById("humidity").innerHTML = data[0].humidity ;
        document.getElementById("wind").innerHTML = data[0].wind;
        document.getElementById("wind-direction").innerHTML=`${data[0].wind_direction} deg`;
        document.getElementById("pressure").innerHTML = `${data[0].pressure}hPa`;
        
        document.getElementById("date").innerHTML = data[0].created_at;
        const weatherIcon = document.getElementById("weather-icon");
        const iconUrl = `https://openweathermap.org/img/w/${data[0].weather_icon}.png`;
        weatherIcon.src = iconUrl;
        
    } catch (error) {
        console.log("Error fetching or parsing data:", error);
    }
}

searchBtn.addEventListener("click", () => {
    checkWeather(searchBox.value);
});
checkWeather("Gateshead");

//Name:Disan yadav
//Id:np03cs4s240166