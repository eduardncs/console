import React, { useState, useEffect, useRef } from 'react';
import { Modal , Button, Container, ListGroup, Dropdown, DropdownButton, ListGroupItem } from 'react-bootstrap';
import $ from 'jquery';
import menuLogo from './../../images/menu.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faHome, faFileAlt, faFolder, faFolderPlus, faEllipsisH } from '@fortawesome/free-solid-svg-icons';
import Sortable from 'sortablejs/modular/sortable.complete.esm.js';
import { etoast, ID } from './../globals';
import { postMan, domain, handleResponse } from './../backend';
import { User , Project } from '../data';

const sortables = {
  value: null,
  get function() { return this.value; },
  set function(val) {
      Array.prototype.isArray(this.value) ? this.value.push(val) : this.value = val;
  },
  add: (val) => {
      Array.prototype.isArray(this.value) ? this.value.push(val) : this.value = val;
  }
}

const FetchMenu = () =>{
  const sandboxMenu = useRef("_R3xd13dsc");
  const [menuItems, setMenuItems] = useState([]);
  
   useEffect(()=>{
    const menu = $("#sandbox").contents().find("."+sandboxMenu.current);
    const rawItems = menu.find(".nav-item");
    let newMenuItems = [];
    rawItems.each((rawItemIndex, rawItem) =>{
      rawItem = $(rawItem);
      const isDropdown = rawItem.hasClass("dropdown");
      let itm = { "text":$.trim($(rawItem.find("a")[0]).text()), "dropdown":isDropdown, "key": ID() }
      if(isDropdown)
      {
        itm.dropdown = [];
        const anchors = rawItem.find(".dropdown-menu a");
        anchors.each((anchorIndex,anchor) =>{
          anchor = $(anchor);
          itm.dropdown = [...itm.dropdown, { "text": anchor.text(), "dropdown": false, "key":ID() }];
        })
      }
      newMenuItems = [...newMenuItems, itm ]
    })
    setMenuItems(newMenuItems);
  }, [])

  useEffect(()=>{
    if(menuItems.length === 0)
      return;
    console.log("Doing this");
    const nestedSortables = $(".nestedSortable");
    for (let i = 0; i < nestedSortables.length; i++) {
        sortables.add = new Sortable(nestedSortables[i], {
            group: 'nested',
            ghostClass: 'active',
            animation: 150,
            fallbackOnBody: true,
            onUpdate: (event) => {
                let inverted = false;
                let neighbour = $(event.item).prev();
                if (neighbour.length === 0) {
                    neighbour = $(event.item).next();
                    inverted = true;
                }
                if (neighbour.length === 0) { console.error("This element has no neighbours"); return; }
/*                 changeIndex($(event.item).data("link"), neighbour.data("link"), inverted);
 */            },
            onAdd: (event) => {
                if ($(event.item).data("isfolder") === true) {
                    etoast("FE: Folders cannot be moved inside other folders yet ...");
                    return;
                }
                let oldParent = $(event.from).data("parent");
                let newParent = $(event.to).data("parent");
                if (typeof oldParent === typeof undefined)
                    oldParent = "menu-container-master";
                if (typeof newParent === typeof undefined)
                    newParent = "menu-container-master";
                let neighbour = $(event.item).prev();
                let inverted = false;
                let isFirst = false;
                if (neighbour.length === 0) {
                    neighbour = $(event.item).next();
                    inverted = true;
                }
                if (neighbour.length === 0) {
                    isFirst = true;
                    neighbour = $(event.item).parent().parent();
                }
/*                 move($(event.item).data("link"), newParent, neighbour.data("link"), inverted, isFirst);
 */            }
        });
    }
  },[menuItems])

  const RenderItems = () =>{
    
    const Item = (props) =>{
      const { text, dropdown, ikey } = props;
      if(dropdown === false)
      {
        return(
          <ListGroup.Item>
            {text}
            <DropdownButton
              className="float-right e-caret-hide"
              variant="white"
              menuAlign="left"
              size="sm"
              title={ <FontAwesomeIcon icon={faEllipsisH} /> }
              id={`btn_${ikey}`}
            >
              <Dropdown.Item eventKey="1" onClick={ () => {  } }>Edit</Dropdown.Item>
              <Dropdown.Item eventKey="2" onClick={ () => {  } }>Remove</Dropdown.Item>
            </DropdownButton>
          </ListGroup.Item>
        )
      }else{
        return(
          <ListGroup.Item>
            {text}
            <DropdownButton
              className="float-right e-caret-hide"
              variant="white"
              menuAlign="left"
              size="sm"
              title={ <FontAwesomeIcon icon={faEllipsisH} /> }
              id={`btn_${ikey}`}
            >
              <Dropdown.Item eventKey="1" onClick={ () => {  } }>Edit</Dropdown.Item>
              <Dropdown.Item eventKey="2" onClick={ () => {  } }>Remove</Dropdown.Item>
            </DropdownButton>
            <ListGroup className="mt-2 nestedSortable">
            {
              dropdown.map((dropdownItem)=>{
                return(
                  <Item text={dropdownItem.text} dropdown={dropdownItem.dropdown} ikey={dropdownItem.key} key={dropdownItem.key} />
                )
              })
            }
            </ListGroup>
          </ListGroup.Item>
        )
      }
    }

    if(menuItems.length === 0)
      return null;
    
    return(
      <ListGroup className="nestedSortable">
        {
          menuItems.map((item)=>{
            return(
                <Item text={item.text} dropdown={item.dropdown} ikey={item.key} key={item.key} />
            )
          })
        }
      </ListGroup>
    )
  }

  return(
    <RenderItems />
  )
}

const Menu = () =>{
    const [show, setShow] = useState(true);
    const [menuItems, setMenuItems] = useState([]);
    const handleClose = () => setShow(false);
    const reloadMenu = () => setMenuItems( <FetchMenu/> ) ;
    useEffect(() =>{
      setMenuItems( <FetchMenu/> )
    },[])


    const editLink = (i) =>{
      console.log("Edit link");
    }

    const removeLink = async (i) =>{
      console.log("RemoveLink");
    }
    
    const addFolder = async() =>{
      console.log("AddFolder");
    }

    const addLink = async () =>{
      console.log("AddLink");
    }

    return (
      <Modal className="left" show={show} onHide={handleClose} animation={true} backdrop="static">
        <Modal.Header closeButton>
          <Modal.Title>Menu editor</Modal.Title>
        </Modal.Header>
        <Modal.Body className="p-0">
          <img src={menuLogo} width="100%" height="150px" alt="Menu logo" />
          <Container id="menu-container-master">
            { menuItems }
          </Container>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={addFolder}>
            <FontAwesomeIcon className="mr-2" icon={faFolderPlus} />
            Add folder
          </Button>
          <Button variant="primary" onClick={addLink}>
          <FontAwesomeIcon className="mr-2" icon={faFileAlt} />
            Add link
          </Button>
        </Modal.Footer>
      </Modal>
    );
  }

export default Menu;