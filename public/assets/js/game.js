//On récupère les id
let idgame = document.querySelector("#idgame").value
let nbrframe = document.querySelector("#nbrframe").value

console.log(idgame)
console.log(nbrframe)

// On attend le chargement du document
window.onload = () => {

    // On va chercher le bouton Sup pour passer a la scene suivante
    let buttonRightUp = document.querySelector("#button-right-up")
    buttonRightUp.addEventListener("click", sceneSup)
    
    // On va chercher le bouton Inf pour passer a la scene precedente
    let buttonRightDown = document.querySelector("#button-right-down")
    buttonRightDown.addEventListener("click", sceneInf)

    // On charge les nouveaux messages
    //setTimeout(setInterval(chargeScene, 2000), 5000)
    
}

function sceneInf(){

        // On instancie XMLHttpRequest
        let xmlhttp = new XMLHttpRequest()

    
        // On gère la réponse
        xmlhttp.onreadystatechange = function(){
            if (this.readyState == 4){
                if(this.status == 200){
                    
                    if(nbrframe >= 1){
                        // On a une réponse
                        // On convertit la réponse en objet JS
                        let message = JSON.parse(this.response)
    
                        console.log("Voici la current frame => "+message.currentFrame)
                        nbrframe = ( message.currentFrame - 1)
                    }else{
                        nbrframe = 0
                    }             
                    
                }else{
                    // On gère les erreurs
                    let erreur = JSON.parse(this.response)
                    alert(erreur.message)
                }
            }
        }
    
        // On ouvre la requête avec le lastid en GET
        xmlhttp.open("GET","/game"+idgame+"/"+nbrframe);
    
        // On envoie
        xmlhttp.send()
    
}

function sceneSup(){

    // On instancie XMLHttpRequest
    let xmlhttp = new XMLHttpRequest()


    // On gère la réponse
    xmlhttp.onreadystatechange = function(){
        if (this.readyState == 4){
            if(this.status == 200){
                

                if(nbrframe = 1){
                    // On a une réponse
                    // On convertit la réponse en objet JS
                    let message = JSON.parse(this.response)

                    console.log( "Voici la current frame => "+message.currentFrame)
                    nbrframe = ( message.currentFrame + 1)
                }else{
                    nbrframe = 1
                }


                
            }else{
                // On gère les erreurs
                let erreur = JSON.parse(this.response)
                alert(erreur.message)
            }
        }
    }

    // On ouvre la requête avec le lastid en GET
    xmlhttp.open("GET","/game"+idgame+"/"+nbrframe);

    // On envoie
    xmlhttp.send()
}

function chargeScene(){

    // On instancie XMLHttpRequest
    let xmlhttp = new XMLHttpRequest()

    
    // On gère la réponse
    xmlhttp.onreadystatechange = function(){
        if (this.readyState == 4){
            if(this.status == 200){
                
                // On a une réponse
                // On convertit la réponse en objet JS
                let message = JSON.parse(this.response)

                console.log(message.currentFrame)

                // for (const [key, value] of Object.entries(message.scenario.frames)) {
                //     console.log(`${key}: ${value}`);
                //   }

                console.log(message)
                // On récupère la div #discussion
                //let discussion = document.querySelector("#frame")
                   
                // On ajoute le contenu avant le contenu de la scene
                //discussion.innerHTML += `<p> Scene ${message.currentFrame} :<br> ${message.scenario.frames.}</p>` 

            
                    
            }else{
                // On gère les erreurs
                let erreur = JSON.parse(this.response)
                alert(erreur.message)
            }
        }
    }

    // On ouvre la requête avec le lastid en GET
    xmlhttp.open("GET","/jouer/partie"+idgame+"/"+nbrframe);

    // On envoie
    xmlhttp.send()
}
