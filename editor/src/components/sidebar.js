import React from 'react';
import {/* Row, Col,  */Media, Container} from 'react-bootstrap';
import '../index.css';
/* import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'; */

const Sidebar = () => {
    return(
        <div className="aside bg-light" style={{backgroundColor:"#F6F6F6"}}>
            <Container className="pt-4">
                <ul className="list-unstyled">
                    <Media as="li">
                        <Media.Body>
                            <a href="/#" className="text-dark" onClick={() => {

                            }}>
                                <h5>Media files</h5>
                            </a>
                        </Media.Body>
                    </Media>
                </ul>
            </Container>
            
        </div>
    )
}

export default Sidebar;