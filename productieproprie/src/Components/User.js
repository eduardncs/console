import React, { useState, useContext, useEffect } from 'react';
import { Nav} from 'react-bootstrap'
import { getCookie, etoast, postMan, handleResponse, isNullOrUndefined } from './Utils';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faUserAlt, faComments, faPlusCircle } from '@fortawesome/free-solid-svg-icons'
import { UserContext } from './../App';

export const getUser = () => {
  const userCookie = getCookie('NCS_USER');
  if(userCookie !== null){
    return JSON.parse(unescape(userCookie));
  }
  return null;
}

export const UserUI = () =>{
  const [userName, setUserName] = useState(["Contul meu"]);
  const User = useContext(UserContext);
  useEffect(() =>{
    if(!isNullOrUndefined(User))
      console.log(User);
  })

  const UserMenu = () =>{
    if(isNullOrUndefined(User))
    {
      return (
        <Nav.Link href="/login" >
          <FontAwesomeIcon className="mr-2" icon={ faUserAlt }/>
          Contul meu
        </Nav.Link>
      )
    }else{
      return (
        <Nav.Link href="#" >
          <img className="rounded mr-1" src="https://console.rosance.com/images/placeholder.jpg" alt="img" width="30px" height="30px"/>
          { userName }
        </Nav.Link>
      )
    }
  }

  const Messages = () =>{
    if(isNullOrUndefined(User))
      return null;

    return(
      <Nav.Link href="#" >
        <FontAwesomeIcon className="mr-1" icon={ faComments } />
        <small>0</small>
      </Nav.Link>
    )
  }

  const AddItem = () =>{
    if(isNullOrUndefined(User))
      return null;
    return(
      <Nav.Link href="/add" >
        <FontAwesomeIcon className="mr-1" icon={ faPlusCircle } />
        <small>Adauga anunt</small>
      </Nav.Link>
    )
  }

  return(
    <>
      <UserMenu />
      <Messages />
      <AddItem />
    </>
  )
}

export const LoginUser = (user,pass) =>{
  if(user === "" || pass === "")
    return etoast("Please fill out all fields");
  postMan(
    "api/registration.req.php",
    "post",
    "loginDefault",
    {
      "User":user,
      "Pass":pass
    }
  ).then(
    (response)=>{
      handleResponse(response);
      if(response.success)
      {
        document.location.href = "/dashboard";
      }
    }
  ).catch(()=>{
    etoast("Something went wrong ,please try again latter");
  })
}

export const RegisterUser = (user,pass,pass2,fname,lname) =>{
  if(user === "" || pass === "" || pass2 === "" || fname === "" || lname === "")
    return etoast("Please fill out all fields");

  if(pass !== pass2)
    return etoast("Passwords should be the same!");

  postMan(
      "api/registration.req.php",
      "post",
      "registerDefault",
      {
        "User":user,
        "Pass":pass,
        "Pass2":pass2,
        "FName":fname,
        "LName":lname
      }
    ).then(
      (response)=>{
        handleResponse(response);
      }
    ).catch(()=>{
      etoast("Something went wrong ,please try again latter");
    })
}
export const ActivateUser = (token, uid) =>{
  if(token === "" || uid === "")
  return etoast("Token or User are empty");
  postMan(
      "api/registration.req.php",
      "post",
      "activateAccount",
      {
        "UID":uid,
        "TOKEN":token
      }
    ).then(
    (response)=>{
      handleResponse(response);
    }
  ).catch(()=>{
    etoast("Something went wrong ,please try again latter");
  })
}