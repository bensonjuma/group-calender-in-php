let today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();
let allMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
let yearNum = document.getElementById("yearNum");

function renderMonths() {
    allMonths.forEach(function (month, i) {
        let months = document.querySelector('.months')
        let monthSpan = document.createElement('span')

        monthSpan.className = 'each-month'
        monthSpan.id = i + 1
        monthSpan.innerHTML = ` ${month} `
        months.append(monthSpan)

        if (i === currentMonth) { // Add class to current month
            monthSpan.classList.add('selected')
            let newp = document.createElement('p')
            newp.className = 'hidden-p'
            newp.hidden = true
            monthSpan.append(newp)
        }

        monthSpan.addEventListener('click', function (e) {
            if (document.querySelector('.hidden-p')) {
                let sel = document.querySelector('.selected')
                sel.className = "each-month"
            }
            e.target.className = 'selected'
            let newp = document.createElement('p')
            newp.className = 'hidden-p'
            newp.hidden = true
            monthSpan.append(newp)

            currentMonth = e.target.id - 1 // Update currentMonth
            renderCalendar(currentMonth, currentYear)
        })
    })
}

function renderCalendar(month, year) {
    let firstDayOfTheMonth = (new Date(year, month)).getDay();
    let daysInMonth = 32 - new Date(year, month, 32).getDate();

    let calendarTable = document.getElementById("calendar-body");
    calendarTable.innerHTML = "";
    yearNum.innerHTML = `Current year ${year}`;

    let date = 1;
    for (let i = 0; i < 6; i++) {
        let week = document.createElement("tr");

        for (let j = 0; j < 7; j++) {
            if (i === 0 && j < firstDayOfTheMonth) {
                let day = document.createElement("td");
                let dateNum = document.createTextNode("");
                day.appendChild(dateNum);
                week.appendChild(day);
            } else if (date > daysInMonth) {
                break;
            } else {
                let day = document.createElement("td");
                let dateNum = document.createTextNode(date);
                if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                    day.setAttribute("title", "today");
                }
                day.appendChild(dateNum);
                week.appendChild(day);
                // add click event to each date if user is looged in
                if (checkCookie() === true) {
                    // console.log("fetching events..." + localStorage.getItem('events'));
                    // check if this day is present in events array
                    let events = localStorage.getItem('events');
                    if (events) {
                        events = JSON.parse(events);
                        let date = `${year}-${month + 1}-${dateNum.textContent}`;
                        // convert date to match YYYY-MM-DD format
                        // JS date string in format "yyyy-m-d"
                        let jsDateStr = date;
                        // Split the JS date string into year, month, and day components
                        let [yearc, monthc, dayc] = jsDateStr.split("-");
                        // Convert the month and day strings to zero-padded strings using the padStart method
                        monthc = monthc.padStart(2, "0");
                        dayc = dayc.padStart(2, "0");
                        // Combine the zero-padded year, month, and day strings into a MySQL-formatted date string
                        let mySqlDateStr = `${yearc}-${monthc}-${dayc}`;
                        // check if this date is present in events array
                        let event = events.find(event => event.date === mySqlDateStr);
                        if (event) {
                            // add green background to this date
                            day.style.backgroundColor = 'green';
                            day.setAttribute("title", event.title);
                            // add click event to this date to view event details
                            day.addEventListener('click', function (e) {
                                e.preventDefault();
                                // show #add-event form on current date position
                                let addEvent = document.querySelector('.add-event');
                                addEvent.style.top = `${e.clientY}px`;
                                addEvent.style.left = `${e.clientX}px`;
                                addEvent.style.display = 'block';
                                // fix this days event to the form
                                let eventTitle = document.querySelector('#event-title');
                                eventTitle.value = event.title;
                                let eventDate = document.querySelector('#event-date');
                                eventDate.value = event.date;
                                let eventDesc = document.querySelector('#event-description');
                                eventDesc.value = event.description;
                                let eventTime = document.querySelector('#event-time');
                                eventTime.value = event.time;
                                let deleteEventBtn = document.querySelector('#delete-event');
                                deleteEventBtn.style.display = 'block';

                            });
                            console.log(`Event ${event.title} found for ${mySqlDateStr}`);
                        }
                    }

                    day.addEventListener('click', function (e) {
                        e.preventDefault();
                        // show #add-event form on current date position
                        let addEvent = document.querySelector('.add-event');
                        addEvent.style.top = `${e.clientY}px`;
                        addEvent.style.left = `${e.clientX}px`;
                        addEvent.style.display = 'block';
                        // replace #delete-event button with #add-event button
                        let deleteEventBtn = document.querySelector('#delete-event');
                        deleteEventBtn.style.display = 'none';
                        let addEventBtn = document.querySelector('#add-event');
                        addEventBtn.style.display = 'block';
                        // add this date to #event-date input
                        let eventDate = document.querySelector('#event-date');
                        eventDate.value = `${year}-${month + 1}-${dateNum.textContent}`;
                        let event = document.querySelector('#event-date').value;
                        console.log(`Event ${event} added to ${year}-${month + 1}-${dateNum.textContent}`);
                    });
                }
                date++;
                day.id = `${year}${String(month + 1).padStart(2, '0')}${String(dateNum.textContent).padStart(2, '0')}`
                day.className = 'dates'

            }
        }
        calendarTable.appendChild(week);
    }
}

function nextYear() {
    let nextYearBtn = document.querySelector('.next-year');
    nextYearBtn.addEventListener('click', function (e) {
        e.preventDefault();
        currentYear++;
        renderCalendar(currentMonth, currentYear);
    });
}

function previousYear() {
    let prevYearBtn = document.querySelector('.previous-year');
    prevYearBtn.addEventListener('click', function (e) {
        e.preventDefault();
        currentYear--;
        renderCalendar(currentMonth, currentYear);
    });
}

renderMonths()
renderCalendar(currentMonth, currentYear);
nextYear()
previousYear()

// function to check if loggedIn cookie exists
function checkCookie() {
    let loggedIn = getCookie("loggedIn");
    if (loggedIn !== "") {
        return true;
    } else {
        return false;
    }
}
function getCookie(user) {
    var cookieArr = document.cookie.split(";");
    for (var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        if (user == cookiePair[0].trim()) {
            return decodeURIComponent(cookiePair[1]);
        }
    }
    return null;
}
if (checkCookie() === false) {
    console.log("You are not logged in");
} else {
    console.log("You are logged in");
    // fetch user's events from database
    fetchEvents();
}
// fetch events from database
function fetchEvents() {
    // send request to server
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_events.php', true);
    xhr.onload = function () {
        if (this.status == 200) {
            // save events in array
            let events = JSON.parse(this.responseText);
            // console.log(events);
            // save events in localStorage
            localStorage.setItem('events', JSON.stringify(events));

        }
    }
    xhr.send();

}
