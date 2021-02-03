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
    setTimeout(setInterval(chargeScene, 2000), 5000)
    
}

function sceneInf(){

        // On instancie XMLHttpRequest
        let xmlhttp = new XMLHttpRequest()

    
        // On gère la réponse
        xmlhttp.onreadystatechange = function(){
            if (this.readyState == 4){
                if(this.status == 200){
                    
                    // On a une réponse
                    // On convertit la réponse en objet JS
                    let message = JSON.parse(this.response)
                
                    $id =  ( message.id-1 )             
                    
                }else{
                    // On gère les erreurs
                    let erreur = JSON.parse(this.response)
                    alert(erreur.message)
                }
            }
        }
    
        // On ouvre la requête avec le lastid en GET
        xmlhttp.open("GET","/game"+$idGame+"/"+nbrFrame);
    
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
                
                // On a une réponse
                // On convertit la réponse en objet JS
                let message = JSON.parse(this.response)
            
                $idGame =  ( message.idGame + 1 )  

                $idFrame = ( message.idFrame + 1)

                
            }else{
                // On gère les erreurs
                let erreur = JSON.parse(this.response)
                alert(erreur.message)
            }
        }
    }

    // On ouvre la requête avec le lastid en GET
    xmlhttp.open("GET","/game"+$idGame+"/"+nbrFrame);

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

                console.log(message)
                // On récupère la div #discussion
                //let discussion = document.querySelector("#frame")
                   
                // On ajoute le contenu avant le contenu de la scene
                //discussion.innerHTML += `<p> Scene ${message.number} :<br> ${message.text}</p>` 

            
                    
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

