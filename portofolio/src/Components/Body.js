import React, { useState } from 'react';
import { Container, Row, Col, OverlayTrigger, Tooltip, Button, Card, Modal, Form, Spinner } from 'react-bootstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faGithub, faLinkedin, faUnity } from '@fortawesome/free-brands-svg-icons';
import { faFileAlt, faServer, faPeopleCarry, faDatabase, faLaptop, faGlobe, faAngleUp, faMapMarkerAlt, faEnvelope, faPhoneAlt } from '@fortawesome/free-solid-svg-icons';
import Typing from 'react-typing-animation';
import myPhoto from './../images/myphoto.jpeg';
import frontendLogo from './../images/react.svg';
import backendLogo from './../images/code.svg';
import databaseLogo from './../images/database.svg';
import gamesLogo from './../images/game.svg';
import agileLogo from './../images/agile.svg';
import hardwareLogo from './../images/server.svg';
import rosance_desktop from "./../images/rosance_desktop.jpg";
import rosance_mobile from "./../images/rosance_mobile.jpg";
import rosance_console from "./../images/rosance_console.jpg";
import me from "./../images/this.jpg";
import coin_angel from "./../images/coin_angel.jpg";
import coin_angel1 from "./../images/coin_angel1.jpg";
import coin_angel2 from "./../images/coin_angel2.jpg";
import Swal from 'sweetalert2';
import { postMan } from './Backend.js';


const Header = () =>{
    return (
        <section className="masthead" id="home">
            <Container className="h-100">
                <Row className="h-100 d-flex align-items-center">
				<Col md={8} className="text-header">
                    <Container>
                        <h3>Hi , my name is</h3>
                        <h2>Alexandru-Eduard Neacsu</h2>
                        <Typing speed={100} loop={true} startDelay={300} >
                            <h4>Full stack web developer</h4>
                            <Typing.Reset count={1}/>
                        </Typing>
                        <div className="main-social mt-4">
                            <OverlayTrigger placement="bottom" overlay={
                                <Tooltip>
                                    Github profile
                                </Tooltip>
                            }>
                            <a href="https://github.com/eduardncs"><FontAwesomeIcon icon={faGithub} /></a>
                            </OverlayTrigger>
                            <OverlayTrigger placement="bottom" overlay={
                                <Tooltip>
                                    Linkedin profile
                                </Tooltip>
                            }>
                            <a href="https://www.linkedin.com/in/eduard-neacsu-7691071b1/" ><FontAwesomeIcon icon={faLinkedin} /></a>
                            </OverlayTrigger>
                            <OverlayTrigger placement="bottom" overlay={
                                <Tooltip>
                                    Download CV
                                </Tooltip>
                            }>
                            <a href="/neacsualexandrueduard.pdf"><FontAwesomeIcon icon={faFileAlt} /></a>
                            </OverlayTrigger>
                        </div>
                    </Container>
                </Col>
			</Row>
            </Container>
        </section>
    )
}

const About = () =>{

    return(
        <section className="about py-4" id="about">
            <Container className="py-4 py-lg-5 py-md-3">
                <Row>
                    <Col md={4}>
                        <img src={myPhoto} alt="me" className="img-fluid img-thumbnail"/>
                    </Col>
                    <Col md={8}>
                        <h5 className="title-small mb-2">Who am i?</h5>
                        <h3 className="title-big">Hi,I'm Eduard Neacsu ,Full stack developer, Maritime Officer and Software Engineer.</h3>
                        <p className="mt-4"> I've started my journey into coding since I was about 14 years old. I've came a long way since then, I've covered a few languages and I feel most comfortable with FrontEnd Development (JS/ReactJS) and Backend Development(PHP/MySQL/Firebase) but I am no stranger to more complex languages
                            such as C#. On the other hand I am also a maritime officer but this is another story that is not relevant yet 
                        </p>
                        <Button className="my-4" variant="yellow" href="/neacsualexandrueduard.pdf" target="_blank">
                            Download CV
                        </Button>
                    </Col>
                </Row>
            </Container>
        </section>
    )
}

const Skills = () =>{
    return(
    <section id="skills" className="skills">
        <Container className="container py-lg-5">
            <h5 className="title-small text-center">My skillset</h5>
            <h3 className="title-big text-center mb-sm-5 mb-4">Programming and much more</h3>
            <Row>
                <Col lg={6} className="item">
                    <Card>
                        <Card.Body>
                            <Card.Title className="icon">
                                <h4><FontAwesomeIcon icon={faGlobe} className="mb-2 mr-3" /><a href="#url">Frontend development</a></h4>
                            </Card.Title>
                            <div className="text-center">
                                <img src={ frontendLogo } alt ="frontend" className="img-fluid mb-3" style={{height:"250px"}} width="250px" height="250px" />
                            </div>
                            <p>
                                One of my favorite place to code. I simply love Javascript/ Ajax and ReactJS.
                                I like working clean and tide using CSS and I believe that design plays a huge role regarding user management that's why I have a sharp eye for UI/UX design
                            </p>
                        </Card.Body>
                    </Card>
                </Col>

                <Col lg={6} className="item">
                    <Card>
                        <Card.Body>
                            <Card.Title className="icon">
                                <h4><FontAwesomeIcon icon={faLaptop} className="mb-2 mr-3" /><a href="#url">Backend Development</a></h4>
                            </Card.Title>
                            <div className="text-center">
                                <img src={ backendLogo } alt ="backend" className="img-fluid mb-3" style={{width:"100%",height:"250px"}} width="250px" height="250px" />
                            </div>
                            <p>
                                LAMP (Linux, Apache, MySql, Php), the best acronym and in my opinion best solution. 
                                I like to use PHP OOP for the backend and integrating REST API's in my code. 
                                I have my own API's and Security codes that you can see in the Projects area
                            </p>
                        </Card.Body>
                    </Card>
                </Col>

                <Col lg={6} className="item">
                    <Card>
                        <Card.Body>
                            <Card.Title className="icon">
                                <h4><FontAwesomeIcon icon={faDatabase} className="mb-2 mr-3" /><a href="#url">Database Management</a></h4>
                            </Card.Title>
                            <div className="text-center">
                                <img src={ databaseLogo } alt ="database" className="img-fluid mb-3" style={{height:"250px"}} width="250px" height="250px" />
                            </div>
                            <p>
                                Intermediate knowledge with SQL databases(MariaDb, MySql, MSSQL) and with NO-SQL databases such as <a href="https://firebase.google.com" rel="noreferrer" target="_blank" className="text-white">Firebase</a>.
                                Writing queryes, updating, migrating and all usual stuffs that is done with a database. I also have deep knowledge of CPanel and WHM UI and API's aswell
                            </p>
                        </Card.Body>
                    </Card>
                </Col>

                <Col lg={6} className="item">
                    <Card>
                        <Card.Body>
                            <Card.Title className="icon">
                                <h4><FontAwesomeIcon icon={faUnity} className="mb-2 mr-3" /><a href="#url">Application Development</a></h4>
                            </Card.Title>
                            <div className="text-center">
                                <img src={ gamesLogo } alt ="unity games" className="img-fluid mb-3" style={{height:"250px"}} width="250px" height="250px" />
                            </div>
                            <p>
                                I think Unity Engine is one of the best game/application engines in the world. I have beginner knowledge of Unity and C# and how to intergrate any tools with this framework.
                                I also have experience with Google Play platform and all it's posibilities and limitations
                            </p>
                        </Card.Body>
                    </Card>
                </Col>

                <Col lg={6} className="item">
                    <Card>
                        <Card.Body>
                            <Card.Title className="icon">
                            <h4><FontAwesomeIcon icon={faPeopleCarry} className="mb-2 mr-3" /><a href="#url">Team player &amp; agile thinking</a></h4>
                            </Card.Title>
                            <div className="text-center">
                                <img src={ agileLogo } alt ="agile" className="img-fluid mb-3" style={{height:"250px"}} width="250px" height="250px" />
                            </div>
                            <p>
                                I am a team player and I believe works has to be done in an Agile enviroment with everybody participating in meetings.
                                Analythical thinking is one of my strongest points, in the end everything resumes to numbers ... I like planning my moves and brainstorming until an adequate solution comes up
                            </p>
                        </Card.Body>
                    </Card>
                </Col>
                <Col lg={6} className="item">
                    <Card>
                        <Card.Body>
                            <Card.Title className="icon">
                            <h4><FontAwesomeIcon icon={faServer} className="mb-2 mr-3" /><a href="#url">IT Hardware</a></h4>
                            </Card.Title>
                            <div className="text-center">
                                <img src={ hardwareLogo } alt ="hardware" className="img-fluid mb-3" style={{height:"250px"}} width="250px" height="250px"/>
                            </div>
                            <p> I am no stranger to IT networking and Hardware aswell , I am curently managing SC Avimar Veterinary SRL (High tech chicken farm) sice 2018 entire system. CCTV cameras, Central servers LAN computers and much more. I also know how
                                to work with both LINUX AND WINDOWS. And how to identify and fix hardware and softawre related problems</p>
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </Container>
    </section>
    )
}

const Project = (props) =>{
    const [show, setShow] = useState(false);
    const handleShow = () => setShow(true);
    const handleClose = () => setShow(false);
    const { title, contentHTML }  = props;
    return (
    <>
        <Button variant="outline-light" className="btn-sm mr-3" onClick={handleShow}>Read more</Button>
        <Modal size="lg" show={show} onHide={handleClose}>
            <Modal.Header closeButton>
            <Modal.Title>{ title }</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                { contentHTML }
            </Modal.Body>
        </Modal>
    </>
    )
}

const Portofolio = () => {
    return (
<section id="projects" className="projects py-5 py-lg-5">
    <Container>
        <h5 className="title-small text-center">Services &amp; Portofolio</h5>
        <h3 className="title-big text-center mb-sm-5 mb-4">What I did so far</h3>

        <Container fluid className="pt-4 px-3">
            <Row>
                <Col md={3}>
                    <Card className="h-100">
                        <Card.Body className="m-0 p-0 text-center">
                            <img 
                                src={ rosance_desktop }
                                alt="Rosance is my biggest ambition. It is a presentation website for the actual business a fully functional and personal built Website Builder. Using ES6 Modules and PHP . It features everything I can do" 
                                className="img-fluid"
                            />
                        </Card.Body>
                        <Card.Footer>
                            <Project 
                                title="Rosance" 
                                contentHTML={
                                <Row>
                                    <Col md={6}>
                                    <div className="text-sm"><small>*This is just the presentation website*</small></div>
                                    My biggest ambition and dream.<br/> 
                                    I believe this has a huge potential because of the simplicity of everything.
                                    Rosance is an alpha production website builder , still under heavy development and testing. <br/>
                                    It basicaly combines : 
                                    <ul>
                                        <li>UI/UX</li>
                                        <li>FrontEnd</li>
                                        <li>BackEnd</li>
                                        <li>Server management</li>
                                        <li>Resources allocation</li>
                                    </ul>
                                    It's a proof of concept and proves my skills.
                                    You will find fully functional:
                                    <ul>
                                        <li>Templates
                                            <ul>
                                                <li>Blog platform</li>
                                                <li>Galleries</li>
                                                <li>Portofolios</li>
                                                <li>E-Commence</li>
                                            </ul>
                                        </li>
                                        <li>Features such as 
                                            <ul>
                                                <li>Analythics</li>
                                                <li>User management</li>
                                                <li>Vouchers</li>
                                                <li>SEO settings</li>
                                                <li>much more ...</li>
                                            </ul>
                                        </li>
                                    </ul>
                                    
                                </Col>
                                <Col md={6} className="text-center">
                                    <img src={rosance_desktop} alt="" className="img-fluid"/>
                                    <img src={rosance_mobile} alt="" className="img-fluid mt-2"/>
                                </Col>
                            </Row>
                                }
                            />
                            <Button href="https://rosance.com" variant="outline-light" className="btn-sm mr-3"><FontAwesomeIcon icon={ faGlobe } /></Button>
                            <Button href="https://github.com/eduardncs/rosance" variant="outline-light" className="btn-sm"><FontAwesomeIcon icon={faGithub} /></Button>
                        </Card.Footer>
                    </Card>
                </Col>
                <Col md={3}>
                    <Card className="h-100">
                        <Card.Body className="m-0 p-0 text-center">
                            <img 
                                src={ rosance_console }
                                alt="Actual console of Rosance. With all it's features. Login/ Register/ Email activation/ Account management/ Website editor/ Blog platform and implemented Firebase/Google/Facebook SDK " 
                                className="img-fluid"
                                />
                        </Card.Body>
                        <Card.Footer>
                            <Project
                             title="Rosance Console"
                             contentHTML={
                                <Row>
                                    <Col md={6}>
                                        Rosance Console is the core and the brain of Rosance ecosystem. The ideea behind this is that everything is centralized on VPS around the globe. And when an user is creating an account , he gains access to that specific server, this is done for performace purposes.
                                        <br/> Here users have full access to their website admin page , they can manage everything on their page such as:
                                        <ul>
                                            <li>SEO Settings</li>
                                            <li>Media files</li>
                                            <li>Users</li>
                                            <li>Analythics</li>
                                            <li>Reports</li>
                                            <li>Payment options</li>
                                            <li>Legal documents</li>
                                            <li>etc...</li>
                                        </ul>
                                        Rosance console is made using latest PHP technologies and editor is made using ES6 modules in order to facilitate migrating to ReactJS.
                                        Every template on rosance has a 90+ score on gooogle <a href="https://developers.google.com/speed/pagespeed/insights/">PageSpeed Insights</a>
                                        
                                        <br/>* I will not make this repository public
                                    </Col>
                                    <Col md={6}>
                                        <img src={rosance_console} alt="" className="img-fluid" />
                                    </Col>
                                </Row>
                             }
                             />
                            <Button href="https://console.rosance.com" variant="outline-light" className="btn-sm mr-3"><FontAwesomeIcon icon={ faGlobe } /></Button>
                        </Card.Footer>
                    </Card>
                </Col>
                <Col md={3}>
                    <Card className="h-100">
                        <Card.Body className="m-0 p-0 text-center">
                            <img 
                                src={ coin_angel }
                                alt="Coin Angel is a game made in Unity and C# implementing Firebase Analythics/NO-SQL database/ Facebook SDK/ Unity Ads / In app purchases. It is a multiplayer game but actuay runs on the cloud" 
                                className="img-fluid"
                                />
                        </Card.Body>
                        <Card.Footer>
                            <Project
                                title="Coin Angel"
                                contentHTML={
                                    <Row>
                                        <Col md={6}>
                                            Coin Angel was made as a dare. <br/>
                                            It's made in Unity Engine and written in C#(.NET). I have implemented Firebase analythics and Database SDK's , Facebook SDK's with all their features
                                        </Col>
                                        <Col md={6}>
                                            <img src={ coin_angel1 } alt="coin angel 1" className="img-fluid" />
                                            <img src={ coin_angel2 } alt="coin angel 2" className="img-fluid mt-2" />
                                        </Col>
                                    </Row>
                                }
                            />
                            <Button variant="outline-light" className="btn-sm mr-3"><FontAwesomeIcon icon={ faGlobe } /></Button>
                        </Card.Footer>
                    </Card>
                </Col>
                <Col md={3}>
                    <Card className="h-100">
                        <Card.Body className="m-0 p-0 text-center">
                            <img 
                                src={ me }
                                alt="This website" 
                                className="img-fluid"
                                />
                        </Card.Body>
                        <Card.Footer>
                            <Project
                                title="My portofolio"
                                contentHTML={
                                    <Row>
                                        <Col md={6}>
                                            Also this website. It's made 100% in ReactJS, with just a touch of PHP for the backend
                                        </Col>
                                        <Col md={6}>
                                            <img src={ me } alt="coin angel 1" className="img-fluid" />
                                        </Col>
                                    </Row>
                                }
                            />
                            <Button href="https://eduardncs.com" variant="outline-light" className="btn-sm mr-3"><FontAwesomeIcon icon={ faGlobe } /></Button>
                            <Button href="https://github.com/eduardncs/portofolio" variant="outline-light" className="btn-sm"><FontAwesomeIcon icon={faGithub} /></Button>
                        </Card.Footer>
                    </Card>
                </Col>
            </Row>
        </Container>
    </Container>
</section>
    )
}

const GetInTouch = () =>{

    const toast = (msg) =>{
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        Toast.fire({ icon: 'success', title: msg });
    }
    const etoast = (msg) =>{
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        Toast.fire({ icon: 'error', title: msg });
    }

    const handleResponse = (response) =>{
        if(typeof response.success !== typeof undefined)
        {
            return toast(response.success);
        }else{
            return etoast(response.error);
        }
    }

    const sendMail = async () =>{
        postMan(
            "processors/contact.req.php",
            "post",
            "sendEmail",
            { 
                "name":document.getElementById("name").value,
                "email":document.getElementById("email").value,
                "subject":document.getElementById("subject").value,
                "message":document.getElementById("message").value
             }
        ).then((response) =>{
            handleResponse(response);
        })
    }

    return(
        <section id="contact" className="getInTouch py-5">
            <Container className="py-3 text-center">
                <h6 className="title-small">Get in touch</h6>
                <h3 className="title-big mb-md-5 mb-4">Let's start a project together!</h3>

                <Row>
                    <Col md={4} className="text-light text-left">
                        <div className="">
                            <FontAwesomeIcon className="mr-2" icon={ faMapMarkerAlt } />
                            Constanta, Romania
                        </div>
                        <div className="pt-2">
                            <FontAwesomeIcon className="mr-2" icon={ faEnvelope } />
                            <a href="mailto:contact.eduard.ncs@gmail.com" className="text-light">contact.eduard.ncs@gmail.com</a>
                        </div>
                        <div className="pt-2">
                            <FontAwesomeIcon className="mr-2" icon={ faPhoneAlt } />
                            +40774082782
                        </div>
                    </Col>
                    <Col md={8} className="text-left text-light">
                        <Form id="contactForm" onSubmit={(e)=>{
                            e.preventDefault();
                            console.log(e);
                            sendMail();
                        }}>
                            <Form.Group>
                                <Form.Label>Email address</Form.Label>
                                <Form.Control type="email" placeholder="Enter email" id="email" />
                                <Form.Text className="text-muted">
                               I will never share your email with anyone else.
                                </Form.Text>
                            </Form.Group>
                            <Form.Group>
                                <Form.Label>Your name</Form.Label>
                                <Form.Control type="text" placeholder="Enter your name" id="name" />
                            </Form.Group>
                            <Form.Group>
                                <Form.Label>Subject</Form.Label>
                                <Form.Control type="text" placeholder="Enter subject" id="subject" />
                            </Form.Group>
                            <Form.Group>
                                <Form.Label>Message</Form.Label>
                                <Form.Control as="textarea" rows={3} id="message" />
                            </Form.Group>
                            <Button variant="primary" id="btnSubmit" type="submit" >
                            <Spinner
                                as="span"
                                animation="border"
                                size="sm"
                                role="status"
                                aria-hidden="true"
                                className="mr-2"
                                style={{display:"none"}}
                                />
                                Submit
                            </Button>
                        </Form>
                    </Col>
                </Row>
            </Container>
        </section>
    )
}

const Footer = () =>{
    const moveTop = () =>{
        if(document.scrollingElement.scrollTop === 0) return;
        const scroll = window.scroll;
        scroll({
            top: 0,
            behavior: "smooth"
          });
    }

    return (
        <footer className="py-sm-5 py-4">
            <Container>
                <div className="footer-content">
                    <div className="row">
                        <div className="col-12 text-center">
                            <p className="m-0">Copyright © 2021 Eduard-Alexandru Neacsu</p>
                        </div>
                    </div>
                </div>
            </Container>
            <Button onClick={() => moveTop(1000) } id="movetop" title="Go to top" style={{display:"none"}}>
                <FontAwesomeIcon icon={faAngleUp} />
            </Button>
        </footer>
    )
}

export {
    Header, About, Skills, Portofolio, GetInTouch, Footer
}