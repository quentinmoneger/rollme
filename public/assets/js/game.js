
// Variables globales
let lastid = 0 // id du dernier message affiché
let idGame = document.querySelector("#idgame").value
let frameId = document.querySelector("#frameId").value


// On attend le chargement du document
window.onload = () => {

    $('#carouselFrame').on('slid.bs.carousel', function (e) {
        let mySelectedFrame = e.relatedTarget;
        frameId = mySelectedFrame.dataset.frameId 
        changeFrame(frameId)
      })

    // On charge le currentFrame
    setTimeout(setInterval(chargeFrame, 2000), 5000)

    // On va chercher la zone texte 
    let text = document.querySelector("#text")
    text.addEventListener("keyup", verifEntree)
    
    // On va chercher le bouton valid
    let valid = document.querySelector("#valid")
    valid.addEventListener("click", ajoutMessage)
        
    // On va chercher le bouton valid pour le lancer de dé 
    let validRolz = document.querySelector("#valid-rolz")
    validRolz.addEventListener("click", ajoutMessageRolz)
    
    // On charge les nouveaux messages
    setTimeout(setInterval(chargeMessages, 2000), 5000)
    
}

function changeFrame(frameId){

        // On instancie XMLHttpRequest
        let xmlhttp = new XMLHttpRequest()

    
        // On gère la réponse
        xmlhttp.onreadystatechange = function(){
            if (this.readyState == 4){
                if(this.status == 200){
                           

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

/**
 * Cette fonction vérifie si on a appuyé sur la touche entrée
 */
function verifEntree(e){
    if(e.key == "Enter"){
        ajoutMessage();
    }
}

/**
 * Charge les derniers messages en Ajax et les insère dans la discussion
 */
function chargeMessages(){

    // On instancie XMLHttpRequest
    let xmlhttp = new XMLHttpRequest()

    
    // On gère la réponse
    xmlhttp.onreadystatechange = function(){
        if (this.readyState == 4){
            if(this.status == 200){
                
                // On a une réponse
                // On convertit la réponse en objet JS
                let messages = JSON.parse(this.response)


                // On récupère la div #discussion
                let discussion = document.querySelector("#discussion")

                // On boucle sur l'objet Js
                for(let message of messages){
                    
                    // On ajoute le contenu avant le contenu actuel de discussion
                    discussion.innerHTML += `<p>${message.user.username} :<br> ${message.message}</p>` 

                    //On affiche le dernier Id
                    console.log(message.id)

                    // On met à jour le lastId
                    lastid = message.id
                
                }
            }else{
                // On gère les erreurs
                let erreur = JSON.parse(this.response)
                alert(erreur.message)
            }
        }
    }

    // On ouvre la requête avec le lastid en GET
    xmlhttp.open("GET","/chat/recover"+lastid);

    // On envoie
    xmlhttp.send()
}




/**
 * Cette fonction envoie le message en ajax à un fichier ajoutMessage.php
 */
function ajoutMessage(){
    // On récupère le message
    let message = document.querySelector("#text").value
    
    // On vérifie si le message n'est pas vide
    if(message != ""){
        // On crée un objet JS
        let donnees = {}
        donnees["message"] = message

        

        // On convertit les données en JSON
        let donneesJson = JSON.stringify(donnees)

        // On affiche la requete dans la console
        console.log(donneesJson)

        // On envoie les données en POST en Ajax
        // On instancie XMLHttpRequest
        let xmlhttp = new XMLHttpRequest()

        // On gère la réponse
        xmlhttp.onreadystatechange = function(){
            // On vérifie si la requête est terminée
            if(this.readyState == 4){
                // On vérifie qu'on reçoit un code 200
                if(this.status == 200){
                    // L'enregistrement a fonctionné
                    // On efface le champ texte
                    document.querySelector("#text").value = ""
                }else{
                    // L'enregistrement a échoué
                    //let reponse = JSON.parse(this.response)
                    alert(reponse.message)
                }
            }
        }

        // On ouvre la requête
        xmlhttp.open("POST", '/chat/add'+idGame+'/'+frameId)

        // On envoie la requête en incluant les données
        xmlhttp.send(donneesJson)
    }
}

/**
 * Cette fonction envoie le message en ajax à un fichier ajoutMessage.php
 */
function ajoutMessageRolz(){
    // On récupère le message
    let message1 = document.querySelector("#input1").value
    let message2 = document.querySelector("#input2").value
    let message = message1+message2
    
    // On vérifie si le message n'est pas vide
    if(message != ""){
        // On crée un objet JS
        let donnees = {}
        donnees["message"] = message

        

        // On convertit les données en JSON
        let donneesJson = JSON.stringify(donnees)

        // On affiche la requete dans la console
        console.log(donneesJson)

        // On envoie les données en POST en Ajax
        // On instancie XMLHttpRequest
        let xmlhttp = new XMLHttpRequest()

        // On gère la réponse
        xmlhttp.onreadystatechange = function(){
            // On vérifie si la requête est terminée
            if(this.readyState == 4){
                // On vérifie qu'on reçoit un code 200
                if(this.status == 200){
                    // L'enregistrement a fonctionné
                    // On efface le champ texte
                    document.querySelector("#input1").value = ""
                    document.querySelector("#input2").value = ""
                    
                    // On scroll automatiquement vers le bs
                    element = document.querySelector('.discussion');
                    element.scrollTop = element.scrollHeight;
                }else{
                    // L'enregistrement a échoué
                    //let reponse = JSON.parse(this.response)
                    alert(reponse.message)
                }
            }
        }

        // On ouvre la requête
        xmlhttp.open("POST", '/chat/rolz'+idGame+'/'+frameId)

        // On envoie la requête en incluant les données
        xmlhttp.send(donneesJson)
    }
}