import React from 'react';
import NavbarMain from './Components/NavbarMain.js';
import Searchbox from './Components/Searchbox.js';
import Paper from '@material-ui/core/Paper';
import Grid from '@material-ui/core/Grid';
import Typography from '@material-ui/core/Typography';
import Link from '@material-ui/core/Link';
import { makeStyles } from '@material-ui/core/styles';
import Footer from './Footer';

const useStyles = makeStyles((theme) => ({
  mainFeaturedPost: {
    position: 'relative',
    backgroundColor: theme.palette.grey[800],
    color: theme.palette.common.white,
    marginBottom: theme.spacing(4),
    marginLeft: theme.spacing(4),
    marginRight: theme.spacing(4),
    backgroundImage: 'url(https://source.unsplash.com/random)',
    backgroundSize: 'cover',
    backgroundRepeat: 'no-repeat',
    backgroundPosition: 'center',
  },
  overlay: {
    position: 'absolute',
    top: 0,
    bottom: 0,
    right: 0,
    left: 0,
    backgroundColor: 'rgba(0,0,0,.3)',
  },
  mainFeaturedPostContent: {
    position: 'relative',
    padding: theme.spacing(3),
    [theme.breakpoints.up('md')]: {
      padding: theme.spacing(6),
      paddingRight: 0,
    },
  },
}));

const Home = () =>{
  const classes = useStyles();
  return(
    <>
      <NavbarMain/>
      <Searchbox />
      <Paper className={classes.mainFeaturedPost} style={{ backgroundImage: `url(https://source.unsplash.com/random)`}}>
        {<img style={{ display: 'none' }} src="https://source.unsplash.com/random" alt="ceva descriere" />}
        <div className={classes.overlay} />
        <Grid container>
          <Grid item md={6}>
            <div className={classes.mainFeaturedPostContent}>
              <Typography component="h1" variant="h3" color="inherit" gutterBottom>
                Vremurile s-au schimbat
              </Typography>
              <Typography variant="h5" color="inherit" paragraph>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea, architecto. Consequatur repellat tempore libero, ullam consectetur deserunt officiis nobis nostrum nesciunt iste commodi sunt, aut, aspernatur cupiditate laudantium veritatis ad?
              </Typography>
              <Link variant="subtitle1" href="/register">
                Alatura-te
              </Link>
            </div>
          </Grid>
        </Grid>
      </Paper>
      <Footer/>
    </>
  )
}

export default Home;