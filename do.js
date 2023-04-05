Date.prototype.getWeek = function() {
    var date = new Date(this.getTime());
    date.setHours(0, 0, 0, 0);
    date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
    var week1 = new Date(date.getFullYear(), 0, 4);
    return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000
                          - 3 + (week1.getDay() + 6) % 7) / 7);
  }
  
Date.prototype.getWeekYear = function() {
    var date = new Date(this.getTime());
    date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
    return date.getFullYear();
}

function drawDayRepetative () {
    let tasks = document.querySelectorAll(".task");
    for (task of tasks) {
        
        if (task.getAttribute("data-repetition") === "day") {
            for (let i=0;i<7;i++) {
                let clone = task.cloneNode(true);
                let content = document.querySelectorAll(".den")[i].querySelector(".den-content");
                let form = content.querySelector("form");
    
                if (!content.contains(task)) {
                content.insertBefore(clone,form);
                }
                
            }
        }

    }
}

const modalRepeat = document.querySelector("#modal-repeat");
const modalRepeatName = document.querySelector("#modal-repeat-name");

drawDayRepetative();

document.addEventListener("click", function(e){

    if (e.target.closest(".task span")) {
        let day = e.target.parentNode.getAttribute("data-day");
        let taskid = e.target.parentNode.getAttribute("data-taskid");
        let done = e.target.parentNode.getAttribute("data-state");
        let name = e.target.parentNode.textContent;

        let package = new FormData();
        package.append('taskid', taskid);
        package.append('id', day);
        package.append('done', done);
        package.append('name', name);


        fetch("insert.php",
            {
                body: package,
                method: "post"
            })
            .then((response) => response.text())
            .then((data) => {
                if (data) {
                    e.target.parentNode.setAttribute("data-state",data);
                }
            })
    }

    if (e.target.closest("#ctrl-toggle")) {
        let button = e.target.closest("#ctrl-toggle");
        button.classList.toggle("pressed");
        document.body.classList.toggle("red");
    }

    if (e.target.closest(".pick-date")) {
        let dialog = prompt("Vyberte den ve formátu YYYY-MM-DD:");
        if (dialog) {
            let newDate = new Date(dialog);
            window.location.href = "index.php?week="+newDate.getWeek()+"&year="+newDate.getWeekYear();
        }
    }
    if (e.target.closest(".task .buttons button")) {
        let task = e.target.closest(".task");
        let span = task.querySelector("span");
        let day = task.getAttribute("data-day");
        let taskid = task.getAttribute("data-taskid");
        let done = task.getAttribute("data-state");
        let name = task.textContent;

        let package = new FormData();
        package.append('taskid', taskid);
        package.append('id', day);
        package.append('done', done);
        package.append('name', name);

        if (e.target.classList.contains("delete")) {
            fetch("delete.php",
            {
                body: package,
                method: "post"
            })
            .then((response) => response.text())
            .then((data) => {
                if (data == "1") {
                    task.parentNode.removeChild(task);
                }
            })
        }
        if (e.target.classList.contains("rename")) {

            let renamed = prompt("Nový název", name);
            package.set("name", renamed);
            if (renamed) {
                name = renamed;
                fetch("rename.php",
            {
                body: package,
                method: "post"
            })
            .then((response) => response.text())
            .then((data) => {
                span.textContent = data;
            })
            }
            
        }
        if (e.target.classList.contains("repeat")) {
            let repetition = task.getAttribute("data-repetition");
            modalRepeat.setAttribute("data-taskid", task.getAttribute("data-taskid"));
            modalRepeat.setAttribute("data-id", task.getAttribute("data-day"));
            modalRepeat.classList.remove("hide");
            modalRepeatName.textContent = "Úkol: "+name;
            modalRepeat.querySelector("input#"+repetition).checked = "checked";
        }
    }

    if (e.target.id == "repeat-form-close") {
        modalRepeat.classList.add("hide");
    }
    if (e.target.id == "repeat-form-send") {
        let selected = modalRepeat.querySelector("input[name=repeat-radio]:checked").value;
        let package = new FormData();
        let taskid = modalRepeat.getAttribute("data-taskid");
        let id = modalRepeat.getAttribute("data-id");
        package.append('taskid', taskid);
        package.append('id', id);
        if (selected) {
            package.set("repetition", selected);

                fetch("repeat.php",
            {
                body: package,
                method: "post"
            })
            .then((response) => response.text())
            .then((data) => {
                if (data === selected) {

                let task = document.querySelector(".task[data-taskid='"+taskid);
                task.setAttribute("data-repetition", selected);
                
                if (selected === "day") {
                    for (let i=0;i<7;i++) {
                        let clone = task.cloneNode(true);
                        clone.setAttribute("data-taskid","");
                        clone.setAttribute("data-day","");
                        clone.className = "faux-task";
                        let content = document.querySelectorAll(".den")[i].querySelector(".den-content");
                        let form = content.querySelector("form");

                        if (!content.contains(task)) {
                        content.insertBefore(clone,form);
                        }
                        
                    }
                }

                modalRepeat.classList.add("hide");
            }
            })
            }

    }

});



document.addEventListener("keydown", function(e){

   if (e.code == "ControlLeft") {
        document.body.classList.add("red");
        document.querySelector("#ctrl-toggle").classList.add("pressed");
   }

});

document.addEventListener("keyup", function(e){

    if (e.code == "ControlLeft") {
         document.body.classList.remove("red");
         document.querySelector("#ctrl-toggle").classList.remove("pressed");
    }
 
 })