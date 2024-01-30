var csc = {
    // (A) INIT
    segments : ["country", "state", "city"], // segment names
    select : [], // html selectors
    init : () => {
      for (let s of csc.segments) {
        csc.select.push(document.getElementById("s-"+s));
      }
      csc.load(0);
    },
   
    // (B) AJAX LOAD COUNTRY-STATE-CITY
    // 0 = COUNTRY, 1 = STATES, 2 = CITIES
    load : segment => {
      // (B1) FORM DATA
      var data = new FormData();
      data.append("segment", csc.segments[segment]);
      if (segment==1 || segment==2) { data.append("country", csc.select[0].value); }
      if (segment==2) { data.append("state", csc.select[1].value); }
   
      // (B2) AJAX FETCH
      fetch("2-ajax.php", { method: "POST", body: data })
      .then(res => res.json())
      .then(res => {
        // (B2-1) DRAW OPTIONS
        csc.select[segment].innerHTML = "";
        for (let r of res) {
          let o = document.createElement("option");
          o.value = r[0]; o.innerHTML = r[1];
          csc.select[segment].appendChild(o);
        }
   
        // (B2-2) CASCADE LOAD NEXT SEGMENT
        if (segment<2) { segment++; csc.load(segment); }
      })
      .catch(err => console.error(err));
    }
  };
  window.onload = csc.select = [COUNTRY, STATE, CITY];