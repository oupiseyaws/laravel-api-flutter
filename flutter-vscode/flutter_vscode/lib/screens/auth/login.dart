import 'dart:io';

import 'package:device_info_plus/device_info_plus.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';

import '../../providers/auth_provider.dart';

class Login extends StatefulWidget {
  const Login({super.key});

  @override
  LoginState createState() => LoginState();
}

class LoginState extends State<Login> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final emailController = TextEditingController();
  final passwordController = TextEditingController();

  String errorMessage = '';

  late String deviceName;

  @override
  void initState() {
    super.initState();
    getDeviceName();
  }

  Future<void> getDeviceName() async {
    try {
      DeviceInfoPlugin deviceInfo = DeviceInfoPlugin();
      if (Platform.isAndroid) {
        AndroidDeviceInfo androidInfo = await deviceInfo.androidInfo;
        setState(() {
          deviceName = androidInfo.model;
        });
      } else if (Platform.isIOS) {
        IosDeviceInfo iosInfo = await deviceInfo.iosInfo;
        setState(() {
          deviceName = iosInfo.name ?? 'Unknown Device';
        });
      }
    } catch (e) {
      setState(() {
        deviceName = 'Could not retrieve device name';
      });
    }
  }

  Future<void> submit() async {
    final form = _formKey.currentState;
    if (!form!.validate()) {
      return;
    }
    final AuthProvider provider =
        Provider.of<AuthProvider>(context, listen: false);
    try {
      await provider.login(
          emailController.text, passwordController.text, deviceName);
    } catch (Exception) {
      setState(() {
        errorMessage = Exception.toString().replaceAll('Exception: ', '');
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text('Login'),
        ),
        body: Container(
            color: Theme.of(context).primaryColor,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: <Widget>[
                Card(
                  elevation: 0,
                  margin: EdgeInsets.only(left: 20, right: 20),
                  child: Padding(
                    padding: EdgeInsets.all(20.0),
                    child: Form(
                      key: _formKey,
                      child: Column(
                        children: <Widget>[
                          TextFormField(
                            keyboardType: TextInputType.emailAddress,
                            controller: emailController,
                            validator: (String? value) {
                              // Validation condition
                              if (value!.trim().isEmpty) {
                                return 'Please enter email';
                              }
                              return null;
                            },
                            decoration: InputDecoration(
                              border: OutlineInputBorder(),
                              labelText: 'Email',
                            ),
                          ),
                          SizedBox(height: 20), // Acts as a spacer
                          TextFormField(
                            keyboardType: TextInputType.visiblePassword,
                            controller: passwordController,
                            obscureText: true,
                            autocorrect: false,
                            enableSuggestions: false,
                            validator: (String? value) {
                              // Validation condition
                              if (value!.isEmpty) {
                                return 'Please enter password';
                              }
                              return null;
                            },
                            decoration: InputDecoration(
                              border: OutlineInputBorder(),
                              labelText: 'Password',
                            ),
                          ),
                          SizedBox(height: 20), // Acts as a spacer
                          ElevatedButton(
                            onPressed: () {
                              submit();
                            },
                            style: ElevatedButton.styleFrom(
                              primary: Colors.purple,
                              onPrimary: Colors.white,
                              minimumSize: Size(double.infinity, 40),
                            ),
                            child: Text('Login'),
                          ),
                          Text(errorMessage,
                              style: TextStyle(color: Colors.red)),
                          Padding(
                            padding: EdgeInsets.only(top: 20),
                            // Different way to add padding
                            child: InkWell(
                                child: Text('Register new User'),
                                onTap: () =>
                                    Navigator.pushNamed(context, '/register')),
                          )
                        ],
                      ),
                    ),
                  ),
                )
              ],
            )));
  }
}
