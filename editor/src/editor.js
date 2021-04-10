import React from 'react';
import NavbarTop from './components/navbar';
import Sidebar from './components/sidebar';
import { Overlay} from './components/globals';
import Sandbox from './components/sandbox';
import { Container } from 'react-bootstrap';

const Editor = () => {
return (
    <>
      <Overlay/>
      <NavbarTop/>
      <div id="externalModuleContainer"></div>
      <div className="wrapper d-flex justify-content-start">
        <Sidebar />
        <Container fluid className="f-flex m-0 p-0 justify-content-center text-center">
          <Sandbox />
        </Container>
      </div>
    </>
    )
}

export default Editor