import React, { useEffect } from 'react';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import CssBaseline from '@material-ui/core/CssBaseline';
import TextField from '@material-ui/core/TextField';
import FormControlLabel from '@material-ui/core/FormControlLabel';
import Checkbox from '@material-ui/core/Checkbox';
import Link from '@material-ui/core/Link';
import Paper from '@material-ui/core/Paper';
import Box from '@material-ui/core/Box';
import Grid from '@material-ui/core/Grid';
import LockOutlinedIcon from '@material-ui/icons/LockOutlined';
import Typography from '@material-ui/core/Typography';
import { makeStyles } from '@material-ui/core/styles';
import { RegisterUser, ActivateUser } from './Components/User';
import { HTTP_GET, getCookie } from './Components/Utils';

function Copyright() {
  return (
    <Typography variant="body2" color="textSecondary" align="center">
      {'Copyright © '}
      <Link color="inherit" href="https://productieproprie.ro/">
        Productie Proprie
      </Link>{' '}
      {new Date().getFullYear()}
      {'.'}
    </Typography>
  );
}

const useStyles = makeStyles((theme) => ({
  root: {
    height: '100vh',
  },
  image: {
    backgroundImage: 'url(https://source.unsplash.com/random)',
    backgroundRepeat: 'no-repeat',
    backgroundColor:
      theme.palette.type === 'light' ? theme.palette.grey[50] : theme.palette.grey[900],
    backgroundSize: 'cover',
    backgroundPosition: 'center',
  },
  paper: {
    margin: theme.spacing(8, 4),
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
  },
  avatar: {
    margin: theme.spacing(1),
    backgroundColor: theme.palette.secondary.main,
  },
  form: {
    width: '100%', // Fix IE 11 issue.
    marginTop: theme.spacing(1),
  },
  submit: {
    margin: theme.spacing(3, 0, 2),
  },
}));

export const Register = () =>{
  useEffect(()=>{
    const user = getCookie("NCS_USER");
    if(user !== null)
    {
      document.location.href = "/dashboard";
    }
  },[])
  useEffect(() =>{
    const token = HTTP_GET("token");
    const uid = HTTP_GET("uid");
    if(!token || !uid)
      return;
    ActivateUser(token,uid);
  },[]);
  const classes = useStyles();

  return (
    <Grid container component="main" className={classes.root}>
      <CssBaseline />
      <Grid item xs={false} sm={4} md={7} className={classes.image} />
      <Grid item xs={12} sm={8} md={5} component={Paper} elevation={6} square>
        <div className={classes.paper}>
          <Avatar className={classes.avatar}>
            <LockOutlinedIcon />
          </Avatar>
          <Typography component="h1" variant="h5">
            Inregistrare
          </Typography>
          <form className={classes.form} onSubmit={
            (e)=>{
              e.preventDefault();
              const email = document.getElementById("email").value,
              pass1 = document.getElementById("pass").value,
              pass2 = document.getElementById("pass2").value,
              fname = document.getElementById("fname").value,
              lname = document.getElementById("lname").value;
              RegisterUser(email,pass1,pass2,fname,lname);
            }
          }>
            <Grid container spacing={1}>
              <Grid item xs={12} sm={6}>
                <TextField
                variant="outlined"
                margin="normal"
                required
                fullWidth
                id="fname"
                label="First name"
                name="fname"
                autoComplete="First name"
                autoFocus
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                variant="outlined"
                margin="normal"
                required
                fullWidth
                id="lname"
                label="Last name"
                name="lname"
                autoComplete="Last name"
                autoFocus
                />
              </Grid>
              <Grid item xs={12} sm={12}>
                <TextField
                variant="outlined"
                margin="normal"
                type="email"
                required
                fullWidth
                id="email"
                label="Email Address"
                name="email"
                autoComplete="email"
                autoFocus
                />
              </Grid>
              <Grid item xs={12} sm={12}>
                <TextField
                variant="outlined"
                margin="normal"
                required
                fullWidth
                name="pass"
                label="Password"
                type="password"
                id="pass"
                autoComplete="current-password"
                />
              </Grid>
              <Grid item xs={12} sm={12}>
                <TextField
                variant="outlined"
                margin="normal"
                required
                fullWidth
                name="pass2"
                label="Password again"
                type="password"
                id="pass2"
                autoComplete="current-password-2"
                />
              </Grid>
              <Grid item xs={12} sm={12}>
                <FormControlLabel
                  control={<Checkbox value="remember" color="primary" required />}
                  label="I have read and I agree with Terms of Service and Privacy policy"
                  required
                />
              </Grid>
              <Grid item xs={12} sm={12}>
                <Button
                type="submit"
                fullWidth
                variant="contained"
                color="primary"
                className={classes.submit}
                >
                Sign me up
                </Button>
              </Grid>
            </Grid>
            <Grid container>
              <Grid item>
                <Link href="/login" variant="body2">
                  {"Already have an account? Sign in"}
                </Link>
              </Grid>
            </Grid>
            <Box mt={5}>
              <Copyright />
            </Box>
          </form>
        </div>
      </Grid>
    </Grid>
  );
}
export default Register