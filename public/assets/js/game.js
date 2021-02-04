
let idGame = document.querySelector("#idgame").value


// On attend le chargement du document
window.onload = () => {

    $('#carouselFrame').on('slid.bs.carousel', function (e) {
        let mySelectedFrame = e.relatedTarget;
        let frameId = mySelectedFrame.dataset.frameId 
        console.log(frameId)
        changeFrame(frameId)
      })


    
    // On charge le currentFrame
    setTimeout(setInterval(chargeFrame, 2000), 5000)
    
}

function changeFrame(frameId){

        // On instancie XMLHttpRequest
        let xmlhttp = new XMLHttpRequest()

    
        // On gère la réponse
        xmlhttp.onreadystatechange = function(){
            if (this.readyState == 4){
                if(this.status == 200){
                           

                // On a une réponse
                // On convertit la réponse en objet JS
                let message = JSON.parse(this.response)



                }else{
                    // On gère les erreurs
                    let erreur = JSON.parse(this.response)
                    alert(erreur.message)
                }
            }
        }
        
        // On ouvre la requête avec le lastid en GET
        xmlhttp.open("GET","/partie/"+idGame+"/"+frameId);
    
        // On envoie
        xmlhttp.send()
    
}


function chargeFrame(){

    // On instancie XMLHttpRequest
    let xmlhttp = new XMLHttpRequest()

    
    // On gère la réponse
    xmlhttp.onreadystatechange = function(){
        if (this.readyState == 4){
            if(this.status == 200){
                
                // On a une réponse
                // On convertit la réponse en objet JS
                let currentFrame = this.response

 

                console.log(currentFrame)

                // On récupère la div #discussion
                let dashboard = document.querySelector("#dashboard")
                let imgsrc = document.querySelector("#imgsrc")

                imgsrc.setAttribute("src", "/"+currentFrame)

            }else{
                // On gère les erreurs
                let erreur = JSON.parse(this.response)
                alert(erreur.message)
            }
        }
    }
    
    // On ouvre la requête avec le lastid en GET
    xmlhttp.open("GET","/jouer/partie/"+idGame);

    // On envoie
    xmlhttp.send()
}
