import React, { useEffect } from 'react';
import { Spinner } from 'react-bootstrap';
import { NavbarMain } from './Components/Navbar';
import { Header, About, Skills, Portofolio, GetInTouch, Footer } from './Components/Body';
import './App.css';

export const domain = "https://eduardncs.com/";

const App = () =>{

  useEffect(() =>{
    setTimeout(() =>{
      document.getElementById("preloader").style.display = 'none';
    }, 500)
    window.onscroll = function() {
      (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) ? document.getElementById("movetop").style.display = "block"
      : document.getElementById("movetop").style.display = "none";
      }
  },[])
  
  return (
    <>
      <Preloader />
      <NavbarMain/>
      <Header />
      <About />
      <Skills />
      <Portofolio />
      <GetInTouch />
      <Footer />
    </>
  )
}

const Preloader = () => {
  return (
    <div id="preloader" className="overlay">
        <div className="container-fluid h-100 d-flex">
            <div className="mx-auto align-self-center text-center">
              <Spinner animation="border" />
                <h2 className="mt-2 dots">Just a second , page is loading <span>.</span><span>.</span><span>.</span></h2>
            </div>
        </div>
    </div>
  )
}

export default App;
