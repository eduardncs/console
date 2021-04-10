/* eslint-disable no-use-before-define */
import {useState, useEffect, useRef, useContext} from 'react';
import { makeStyles } from '@material-ui/core/styles';
import NavbarMain from './Components/NavbarMain';
import { Container, Row, Col, Modal, ListGroup } from 'react-bootstrap';
import TextField from '@material-ui/core/TextField';
import Paper from '@material-ui/core/Paper';
import addMedia from './images/add-media.png';
import Card from '@material-ui/core/Card';
import CardMedia from '@material-ui/core/CardMedia';
import { postMan, postManPlus } from './Components/Utils';
import Autocomplete from '@material-ui/lab/Autocomplete';
import Button from '@material-ui/core/Button';
import Checkbox from '@material-ui/core/Checkbox';
import { UserContext } from './App.js';
import Footer from './Footer';

const useStyles = makeStyles({
  root: {
    maxWidth: 345,
  },
  media: {
    height: 140,
  },
});

const Categories = () =>{
  const [innerCategory] = useState("Selectati o categorie");
  const [categories, setCategories] = useState([]);
  const [subCategories, setSubCategories] = useState([]);
  const [subSubCategories, setSubSubCategories] = useState([]);
  const [show, setShow] = useState(false);
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  useEffect(()=>{
    //Fetch categories from backend
    postMan(
      "api/data.req.php",
      "post",
      "getCategories",
      {"apiKey":"1234"}
    ).then(
      (data)=>{
        let sel = [];
        for(let i = 0; i < data.length; i++)
        {
          sel = [...sel, data[i]["Name"]];
        }
        setCategories(sel);
      }
    ).catch(()=>{
      console.error("Could not fetch categories from the server!");
    })
},[])

const getSubCategories = (main) => {
  postMan(
    "api/data.req.php",
    "post",
    "getSubCategories",
    {"category":main}
  ).then(
    (data)=>{
      let sel = [];
      for(let i = 0; i < data.length; i++)
      {
        sel = [...sel, data[i]["Name"]];
      }
      setSubCategories(sel);
    }
  ).catch(()=>{
    console.error("Could not fetch categories from the server!");
  })}

  const getSubSubCategories = (main) =>{
    postMan(
      "api/data.req.php",
      "post",
      "getSubCategories",
      {"category":main}
    ).then(
      (data)=>{
        let sel = [];
        for(let i = 0; i < data.length; i++)
        {
          sel = [...sel, data[i]["Name"]];
        }
        setSubSubCategories(sel);
      }
    ).catch(()=>{
      console.error("Could not fetch categories from the server!");
    })}

  return(
  <>
    <TextField 
      className="mt-2" 
      style={{width:"65%"}} 
      required
      readOnly
      id="categoryBtn" 
      label="Categorie" 
      helperText="Alegeti categoria care se potriveste cel mai bine anuntului"
      onClick={ handleShow }
      defaultValue={ innerCategory }
      />
      <Modal show={show} onHide={handleClose} animation={true} centered size="lg">
      <Modal.Header closeButton></Modal.Header>
      <Modal.Body>
        <Row>
          <Col md={4}>
            <ListGroup as="ul">
              {
              categories.map((category)=>{
                return(
                <ListGroup.Item as="li" action 
                  className="categoryList" 
                  key={category} 
                  onClick={
                    (e) =>{
                      if(e.target.innerText === "")
                        return;
                      getSubCategories(e.target.innerText);
                      setSubSubCategories([]);
                    }
                  }>
                  {category}
                </ListGroup.Item>
                )})
              }
            </ListGroup>
          </Col>
          <Col md={4}>
            <ListGroup as="ul">
            {
              subCategories.map((subcategory)=>{
                return(
                <ListGroup.Item as="li" action 
                  className="categoryList" 
                  key={subcategory} 
                  onClick={
                    (e) =>{
                      if(e.target.innerText === "")
                        return;
                      getSubSubCategories(e.target.innerText);
                    }
                  }>
                  {subcategory}
                </ListGroup.Item>
                )})
              }
            </ListGroup>
          </Col>
          <Col md={4}>
          <ListGroup as="ul">
            {
              subSubCategories.map((subsubcategory)=>{
                return(
                <ListGroup.Item as="li" action 
                  className="categoryList" 
                  key={subsubcategory} 
                  onClick={
                  (e) =>{
                    console.log(e.target.innerText);
                    document.getElementById("categoryBtn").value = e.target.innerText;
                    handleClose();
                  }
                  }>
                  {subsubcategory}
                </ListGroup.Item>
                )})
              }
            </ListGroup>
          </Col>
        </Row>
      </Modal.Body>
    </Modal>
  </>
  )
}

const AddForm = () =>{
  const user = useContext(UserContext);
  const MaxCharacters = useRef(70);
  const descriptionMaxCharacters = useRef(10000);
  const [titleKeysLeft, setTitleKeysLeft] = useState(MaxCharacters.current);
  const [contactKeysLeft, setContactKeysLeft] = useState(MaxCharacters.current);
  const [emailKeysLeft, setEmailKeysLeft] = useState(MaxCharacters.current);
  const [descriptionKeysLeft, setDescriptionKeysLeft] = useState(descriptionMaxCharacters.current);

  const [towns, setTowns] = useState([]);
  const [images, setImages] = useState([]);
  const classes = useStyles();
  useEffect(() =>{
    postMan(
      "api/data.req.php",
      "post",
      "getTowns",
      {"apiKey":"1234"}
    ).then((data)=>{
      let sel = [];
      for(let i = 1; i < data.length; i++)
      {
        const n = { "title":data[i][0] };
        sel = [...sel, n];
      }
      setTowns(sel);
    }).catch((ex)=>{
      console.error("Could not fetch towns from the server");
    })
  },[])

  const handleImageChange = (imgs) =>{
    const arr = Array.from(imgs);
    setImages(arr.slice(0,10));
  }

  const ParseUploadedFiles = () =>{
    return(
      images.map((img)=>{
        return (
          <Col key={img.name} md={2}>
            <Card>
              <CardMedia
                className={classes.media}
                image={URL.createObjectURL(img)}
                title={img.name}
              />
            </Card>
          </Col>
        )
      })
    )
  }
  
  return(
    <Container className="mt-4">
      <h1>Adauga anunt</h1>
        <form className="formAdd" onSubmit={(e)=>{
          e.preventDefault();
          console.log(e);
          postManPlus(
            "api/data.req.php",
            "post",
            "addItem",
            {
              "title": document.getElementById("title").value ,
              "category": document.getElementById("categoryBtn").value ,
              "description": document.getElementById("description").value ,
              "contactName": document.getElementById("contactName").value ,
              "contactEmail": document.getElementById("contactEmail").value ,
              "contactPhone": document.getElementById("contactPhone").value ,
              "contactLocation": document.getElementById("contactLocation").value
            },
            document.getElementById("photos").files
          )
        }}>
          <Paper elevation={3}>
            <Container className="pb-2">
              <h3 className="p-1 pt-2">Date generale </h3>
              <TextField style={{width:"65%"}} required id="title" label="Titlu anunt" onChange={
                (e) =>{
                  let no = MaxCharacters.current - e.target.value.length
                  if(no < 0)
                  {
                    e.target.value = e.target.value.substring(0, e.target.value.length - 1);
                    setTitleKeysLeft(0);
                    return;
                  }
                  setTitleKeysLeft(no);
                }
              } helperText={`${titleKeysLeft} caractere ramase`} />
              <Categories />
              <TextField required className="mt-2" fullWidth  id="description" label="Descriere anunt" onChange={
                (e) =>{
                  let no = descriptionMaxCharacters.current - e.target.value.length
                  if(no < 0)
                  {
                    e.target.value = e.target.value.substring(0, e.target.value.length - 1);
                    setDescriptionKeysLeft(0)
                    return;
                  }
                  setDescriptionKeysLeft(no);
                }
              } helperText={`${descriptionKeysLeft} caractere ramase`} multiline={true} rows={10} />
          </Container>
        </Paper>

        <Paper className="mt-3" elevation={3}>
          <Container className="pb-2" direction="row">
            <h3 className="pl-l pt-2">Galerie</h3>
            <small className="pl-1">Adauga imagini care sa descrie cat mai bine produsul tau</small>
            <Container className="p-3 mt-2 border">
              <Row>
                <Col md={2}>
                  <input
                    accept="image/"
                    className=""
                    id="photos"
                    multiple
                    type="file"
                    style={{display:'none'}}
                    onChange= { () => { handleImageChange(document.getElementById("photos").files);}}
                  />
                    <label htmlFor="photos">
                    <img src={addMedia} alt=""
                      className="img-fluid img-add-photo-gallery" />
                    </label>
                </Col>
                <ParseUploadedFiles />
              </Row>
            </Container>
            <small className="text-muted">Maximum 10 imagini cu o marime maxima per imagine de 5MB</small>
          </Container>
        </Paper>

        <Paper className="mt-3" elevation={3}>
          <Container className="pb-4" direction="row">
            <h3 className="pl-l pt-2">Date de contact</h3>

            <TextField className="mt-2" required style={{width:"65%"}}  id="contactName" label="Persoana de contact" onChange={
                (e) =>{
                  let no = MaxCharacters.current - e.target.value.length
                  if(no < 0)
                  {
                    e.target.value = e.target.value.substring(0, e.target.value.length - 1);
                    setContactKeysLeft(0);
                    return;
                  }
                  setContactKeysLeft(no);
                }
              } helperText={`${contactKeysLeft} caractere ramase`}
              defaultValue={ user.FirstName+" "+user.LastName }
              />
              <TextField required className="mt-2" style={{width:"65%"}}  id="contactEmail" label="Adresa de email" onChange={
                (e) =>{
                  let no = MaxCharacters.current - e.target.value.length
                  if(no < 0)
                  {
                    e.target.value = e.target.value.substring(0, e.target.value.length - 1);
                    setEmailKeysLeft(0);
                    return;
                  }
                  setEmailKeysLeft(no);
                }
              } helperText={`${emailKeysLeft} caractere ramase`}
              defaultValue={ user.Email }
              />
              <TextField className="mt-2" style={{width:"45%"}}  id="contactPhone" label="Numar de telefon" />

            <Autocomplete
              id="contactLocation"
              options={towns}
              getOptionLabel={(option) => option.title}
              style={{width:"65%"}}
              className="mt-3"
              renderInput={(params) => <TextField {...params} label="Oras" variant="outlined" />}
            />
              
          </Container>
        </Paper>

        <Paper className="mt-3" elevation={3}>
          <Container className="d-flex align-items-center justify-content-between">
            <div>
             <Checkbox
              id="tosRead"
              name="checkedB"
              color="primary"
              required
              />
            <label htmlFor="tosRead">
              Am citit si sunt de acord cu <a href="/legal" target="_blank">Termenii si Conditiile</a> productieproprie.ro
            </label>
            </div>
            <Button variant="contained" color="primary" className="m-3" type="submit">
              Continua
            </Button>
          </Container>
        </Paper>
      </form>
    </Container>
  )
}

const Add = () =>{
  const user = useContext(UserContext);
  if(user === null)
    document.location.href = "/login";
  return(
    <>
      <NavbarMain />
      <AddForm />
      <Footer/>
    </>
  )
}

export default Add;