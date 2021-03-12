import React from 'react';
import logo from '../images/logo.png';
import { changePerspective } from './globals';
import { Navbar, Nav, OverlayTrigger, Tooltip } from "react-bootstrap";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faLaptop, faMobileAlt} from '@fortawesome/free-solid-svg-icons';

const NavbarTop = () => {
    return (
        <Navbar collapseOnSelect expand="lg" bg="light" variant="light"  style={{backgroundColor:"#F6F6F6"}} sticky="top">
            <Navbar.Brand href="/#">
            <img
                alt="Rosance logo"
                src={logo}
                width="30"
                height="30"
                className="d-inline-block align-top mr-2"
            />
            Rosance Editor <small>V 0.2</small>
            </Navbar.Brand>
            <Navbar.Toggle aria-controls="responsive-navbar-nav" />
            <Navbar.Collapse id="responsive-navbar-nav">
                <Nav className="mx-auto">
                    <OverlayTrigger
                        placement="bottom"
                        delay={{show:100, hide:400}}
                        overlay={
                        <Tooltip id={`tooltip-bottom`}>
                            Desktop perspective
                        </Tooltip>
                        }
                    >

                        <Nav.Link href="#" onClick={(e) => {
                            e.preventDefault();
                            changePerspective('desktop')
                            }} className="bordered-right">
                            <FontAwesomeIcon icon={faLaptop}/>
                        </Nav.Link>
                        
                    </OverlayTrigger>
                    <OverlayTrigger
                        placement="bottom"
                        delay={{show:100, hide:400}}
                        overlay={
                        <Tooltip id={`tooltip-bottom`}>
                            Mobile perspective
                        </Tooltip>
                        }
                    >
                        <Nav.Link href="#" onClick={(e) => {
                            e.preventDefault();
                            changePerspective('mobile')
                        }}>
                            <FontAwesomeIcon icon={faMobileAlt}/>
                        </Nav.Link>
                    </OverlayTrigger>
                </Nav>
            </Navbar.Collapse>
        </Navbar>
    )
}
export default NavbarTop;