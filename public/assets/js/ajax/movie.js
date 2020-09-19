function controlLikes() {
    movieLikes = document.getElementById('movie_likes')
    movieId = document.getElementById('movie_id').innerHTML
    objectXHR = createXHR()
    objectXHR.open('get', 'https://127.0.0.1:8000/movie/3/like', true)
    objectXHR.send(null)
    objectXHR.onreadystatechange = updateLike
    let response = objectXHR.responseText

}


function updateLike() {
    let movieLikes = document.getElementById('movie_likes')
    let movieLikesTitle = document.getElementById('movie_likes_title')
    let movieLikesIconName = document.getElementById('movie_likes_icon_name')
    var resultFormat = '('+ objectXHR.responseText +')'
    var resultObject = eval(resultFormat) 
    let resultStatus = objectXHR.status
   
    if(objectXHR.readyState === 4){  
    
        //correct response case
        if(objectXHR.status === 200){
            console.log(resultObject.likes)
            movieLikes.innerHTML = resultObject.likes
            if(resultObject.likes){
                movieLikesTitle.innerHTML = "i like"
                movieLikesIconName.className = "icon ion-md-heart"
            }
            else{
                movieLikesTitle.innerHTML = "i don't like"
                movieLikesIconName.className = "icon ion-md-heart-empty"
            }
            
            

            
        }
        //error getting case
        else {
            //user error case
            if(objectXHR.status >= 400 && objectXHR.status < 500){
             
            }
            //server error
            else if (objectXHR.status >= 500 && objectXHR.state < 600){
            }
        }
       
    }
    else{
        if(objectXHR.readyState === 0){
          
        }
        else if(objectXHR.readyState === 1){
        }   
        else if(objectXHR.readyState === 2){

        }
        else if(objectXHR.readyState === 3){
        }
        
    }

}

function createXHR(){
    var resultat = null
    try{
        resultat = new XMLHttpRequest()
    }
    catch(Error){
        try{
            resultat = new ActiveXObject("Msxml2.XMLHTTP")
        }
        catch(Error){
            try{
                resultat = new ActiveXObject("Microsoft.XMLHTTP")
            }
            catch(Error){
                resultat = null
            }
        }
    }
    return resultat
}