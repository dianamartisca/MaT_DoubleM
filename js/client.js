const daysSection = document.getElementsByClassName('days')[0];
const monthYear = document.getElementsByClassName('month-year')[0];
const prevButton = document.getElementsByClassName('previous-month')[0];
const nextButton = document.getElementsByClassName('next-month')[0];
const hoursPopup = document.getElementsByClassName('hours-popup')[0];
const hoursAll = document.getElementsByClassName('hours')[0];
const selectedDay = document.getElementsByClassName('selected-day')[0];
const dateTime = document.getElementById('datetime');

let currentDate = new Date();

const availableHours = ["10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00"];
let bookedSlotsByDate = {};

prevButton.addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
});

nextButton.addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
});

function generateCalendar(year, month) {
  daysSection.innerHTML = '';
  let date = new Date(year, month, 1);
  let monthName = date.toLocaleString('default', { month: 'long' });
  monthYear.textContent = `${monthName} ${year}`;
  let daysInMonth = new Date(year, month + 1, 0).getDate();
  let today = new Date();

  for (let i = 1; i <= daysInMonth; i++) {
    const buton = document.createElement('button');
    buton.textContent = i;
    let thisDate = new Date(year, month, i);
    let ziuaSapt = thisDate.getDay();

    let dateKey = `${year}-${(month + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
    let bookedHours = bookedSlotsByDate[dateKey] || [];
    let ziCompletaOcupata = bookedHours.length === availableHours.length;

    

    if (thisDate < new Date(today.getFullYear(), today.getMonth(), today.getDate()) || ziuaSapt === 0 || ziuaSapt === 6) {
      buton.className = 'not-good';
    } else if (ziCompletaOcupata) {
      
      buton.className = 'not-good';
    } else {
      buton.className = 'good';
      buton.addEventListener('click', () => showHours(i, month, year));
    }

    daysSection.appendChild(buton);
  }
}

function showHours(day, month, year) {
  let dateString = `${day.toString().padStart(2, '0')}.${(month + 1).toString().padStart(2, '0')}.${year}`;
  selectedDay.textContent = `Selectati ora pentru ${dateString}`;
  dateTime.value = `${dateString}`;
  hoursAll.innerHTML = '';

  let dateKey = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
  let booked = bookedSlotsByDate[dateKey] || [];

  availableHours.forEach(hour => {
    let buton = document.createElement('button');
    buton.textContent = hour;

    if (booked.includes(hour)) {
      buton.className = 'not-good';
      buton.disabled = true;
    } else {
      buton.className = 'good';
      buton.addEventListener('click', () => {
        dateTime.value = `${dateString} ora ${hour}`;
      });
    }

    hoursAll.appendChild(buton);
  });

  hoursPopup.style.display = 'block';
}

function loadApprovedRequestsAndRenderCalendar() {
  
  fetch("/MaT_DoubleM/my-php-backend/public/index.php/requests")
    .then(res => res.json())
    .then(data => {
      bookedSlotsByDate = {};      

      const toateCereri = data.message || [];
      const aprobate = toateCereri.filter(r => r.status === 'aprobata');
    

      aprobate.forEach(r => {
        const [dateStr, hourStr] = r.date_requested.split(" ");
        const hour = hourStr.substring(0, 5);
        if (!bookedSlotsByDate[dateStr]) {
          bookedSlotsByDate[dateStr] = [];
        }
        bookedSlotsByDate[dateStr].push(hour);
      });

      console.log("📦 Zile ocupate procesate:", bookedSlotsByDate);

      generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });
}

window.addEventListener('DOMContentLoaded', () => {
  loadApprovedRequestsAndRenderCalendar();
});