// Variables globales
let lastid = 0 // id du dernier message affiché

// On attend le chargement du document
window.onload = () => {
    // On va chercher la zone texte
    let texte = document.querySelector("#texte")
    texte.addEventListener("keyup", verifEntree)

    // On va chercher le bouton valid
    let valid = document.querySelector("#valid")
    valid.addEventListener("click", ajoutMessage)

    // On charge les nouveaux messages
    setTimeout(setInterval(chargeMessages, 2000), 5000)
    
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
                    discussion.innerHTML += `<p>${message.userId.username} a écrit: ${message.message}</p>` 

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
 * Cette fonction vérifie si on a appuyé sur la touche entrée
 */
function verifEntree(e){
    if(e.key == "Enter"){
        ajoutMessage();
    }
}

/**
 * Cette fonction envoie le message en ajax à un fichier ajoutMessage.php
 */
function ajoutMessage(){
    // On récupère le message
    let message = document.querySelector("#texte").value
    
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
                    document.querySelector("#texte").value = ""
                }else{
                    // L'enregistrement a échoué
                    //let reponse = JSON.parse(this.response)
                    alert(reponse.message)
                }
            }
        }

        // On ouvre la requête
        xmlhttp.open("POST", '/chat/add')

        // On envoie la requête en incluant les données
        xmlhttp.send(donneesJson)
    }
}