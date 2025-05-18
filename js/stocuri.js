document.addEventListener("DOMContentLoaded", () => { /*execut dupa ce tot codul html e gata */

  const editButtons = document.querySelectorAll(".edit-btn");

  editButtons.forEach((btn) => {

    btn.addEventListener("click", () => 
      {
      const row = btn.closest("tr");
      const cells = row.querySelectorAll("td");

      if (btn.textContent === "Editează") 
        {
        const currentQty = cells[2].textContent.trim();
        cells[2].innerHTML = `<input type='number' value='${currentQty}' min='0' '>`; //inlocuiesc nr cu input
        btn.textContent = "Salvează";
      } 
      else  //daca e salveaza
      {
        const input = cells[2].querySelector("input"); 
        if (input) 
          {
          cells[2].textContent = input.value; //iau val din el si o pun in celula 
          btn.textContent = "Editează"; //back
        }
      }
    });
  });
});
