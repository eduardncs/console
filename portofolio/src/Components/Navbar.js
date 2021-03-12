import React from 'react';
import { Navbar, Nav,  Container } from 'react-bootstrap'

export const NavbarMain = () =>{
    return(
        <Navbar className="ncs-navbar" collapseOnSelect fixed="top" expand="lg" bg="transparent" variant="dark">
        <Container>
            <Navbar.Brand href="#home">Eduard Neacsu</Navbar.Brand>
            <Navbar.Toggle aria-controls="responsive-navbar-nav" />
            <Navbar.Collapse id="responsive-navbar-nav">
            <Nav className="ml-auto">
                <Nav.Link href="#home">Home</Nav.Link>
                <Nav.Link href="#about">About</Nav.Link>
                <Nav.Link href="#skills">Skillset</Nav.Link>
                <Nav.Link href="#projects">Projects</Nav.Link>
                <Nav.Link href="#contact">Contact</Nav.Link>
            </Nav>
            </Navbar.Collapse>
        </Container>
      </Navbar>
    )
}