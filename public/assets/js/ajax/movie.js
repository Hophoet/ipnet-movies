function controlLikes() {
    movieLikes = document.getElementById('movie_likes')
    movieId = document.getElementById('movie_id').innerHTML
    objectXHR = createXHR()
    objectXHR.open('get', 'https://127.0.0.1:8000/movie/'+movieId+'/like', true)
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


function controlComments(){
    movieCommentContent = document.getElementById('movie_comment_content')
    movieId = document.getElementById('movie_id').innerHTML
    objectXHR = createXHR()
    objectXHR.open('post', 'https://127.0.0.1:8000/movie/'+movieId+'/comment?name=ali', true)
    objectXHR.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    params = 'commentContent='+movieCommentContent.value
    movieCommentContent.value = ""
    
    objectXHR.send(params)
    objectXHR.onreadystatechange = updateComment
    
 
}

function updateComment(){
    
    let resultStatus = objectXHR.status
   
    if(objectXHR.readyState === 4){  
    
        //correct response case
        if(objectXHR.status === 200){
            response = objectXHR.responseText
            var resultFormat = '('+response  +')'
            var resultObject = eval(resultFormat)
            if(resultObject.code == 200){ 
                //console.log('SAVE: ')
                addComment(resultObject.comment)
            }
            else{ 
                //console.log('NOT SAVE: ')
                //console.log(resultObject)
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


function addComment(comment){
    let li = document.createElement('li')
    li.className = "comments_item"
    let html = "<div class=comments__autor><img class=comments__avatar src=/assets/img/user.png alt=d><span class=comments__name>"+comment.user+"</span><span class=comments__time>"+comment.date+"</span></div><p class=comments__text>"+comment.content+"</p>"
    li.innerHTML = html
    let noComment = document.getElementById('no_comment')
    if(noComment){
         noComment.hidden = true;
         console.log('no comment')
        }
    document.getElementById('comments_list').append(li)
}