const daysSection = document.getElementsByClassName('days')[0];
const monthYear = document.getElementsByClassName('month-year')[0];
const prevButton = document.getElementsByClassName('previous-month')[0];
const nextButton = document.getElementsByClassName('next-month')[0];
const hoursPopup = document.getElementsByClassName('hours-popup')[0];
const hoursAll = document.getElementsByClassName('hours')[0];
const selectedDay = document.getElementsByClassName('selected-day')[0];
const dateTime = document.getElementById('datetime');

let currentDate = new Date();

const availableHours=["10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00"];


prevButton.addEventListener('click', () => 
  {
  currentDate.setMonth(currentDate.getMonth() - 1);
  generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
  });

nextButton.addEventListener('click', () => 
{
  currentDate.setMonth(currentDate.getMonth() + 1);
  generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
});



function generateCalendar(year, month) 
{
  daysSection.innerHTML = '';
  let date = new Date(year, month, 1);//iau prima zi

  //fix pt calendar sa inceapa cu ziua buna
  let firstDayOfWeek = date.getDay();

  let monthName = date.toLocaleString('default', { month: 'long' });
  monthYear.textContent = `${monthName} ${year}`;
  let daysInMonth=new Date(year,month+1,0).getDate();
  //ziua 0 a lunii urm e ultima zi a lunii anterioare lol
  let today=new Date();
  

  for(let i=1;i<=daysInMonth;i++)
  {
    const buton=document.createElement('button');
    buton.textContent=i;
    let thisDate=new Date(year,month,i);
    
    let ziuaSapt=thisDate.getDay(); 


    if(thisDate<new Date(today.getFullYear(),today.getMonth(),today.getDate()) ||ziuaSapt===0 ||ziuaSapt===6)
      buton.className='not-good';
    else
    {
      buton.className='good';
      buton.addEventListener('click', ()=>showHours(i,month,year));
    }
    
    daysSection.appendChild(buton);
  }

}

function showHours(day,month,year)
{
  let dateString=`${day.toString().padStart(2,'0')}.${(month+1).toString().padStart(2,'0')}.${year}`;
  selectedDay.textContent=`Selectati ora pentru ${dateString}`;
  dateTime.value=`${dateString}`;
  hoursAll.innerHTML='';

  availableHours.forEach(hour =>{
    let buton=document.createElement('button');
    buton.textContent=hour;
    buton.className='good';
    buton.addEventListener('click', ()=>
    {
      dateTime.value=`${dateString} ora ${hour}`;
    });

    hoursAll.appendChild(buton);
  })
}




generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
