import './globals.css'
import { ClerkProvider, SignedIn, SignedOut, RedirectToSignIn   } from '@clerk/nextjs'
import { Inter } from 'next/font/google'
import type { Metadata } from 'next'
import React, { useState } from 'react'
import { auth } from '@clerk/nextjs'
import localFont from 'next/font/local'
import { ToastContainer } from 'react-toastify';
import DashboardLayout from './components/(layout)/DashboardLayout'
import { ReduxProvider } from './store/ReduxProvider'
import { PHProvider } from './providers'
import { enUS } from './components/data/localization'
import 'react-toastify/dist/ReactToastify.css';
const inter = Inter({ subsets: ['latin'] })
const helvetica = localFont({
  src: '../public/fonts/Helvetica.woff',
  variable: '--font-helvetica-bold',
})
const menlo = localFont({
  src: '../public/fonts/MenloRegular.woff',
  variable: '--font-menlo-regular',
})
export const metadata: Metadata = {
  title: 'Javelin UI',
  description: 'Generated by create next app',  
}
export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  const { userId } = auth();
  return (   
    <ClerkProvider appearance={{}} localization={enUS}>
      <html lang="en">
        <head>
          <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-touch-icon.png" />
          <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png" />
          <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png" />
          <link rel="manifest" href="/favicons/site.webmanifest" />
          <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5" />
          <link rel="shortcut icon" href="/favicons/favicon.ico" />
          <meta name="msapplication-TileColor" content="#da532c" />
          <meta name="msapplication-config" content="/favicons/browserconfig.xml" />
          <meta name="theme-color" content="#ffffff" />
        </head>
        <body className={`${inter.className} ${helvetica.variable} ${menlo.variable}`} >          
          <ReduxProvider> 
            <PHProvider>             
              <DashboardLayout>{children}</DashboardLayout>             
              <ToastContainer/>
            </PHProvider>    
          </ReduxProvider>
        </body>
      </html>
    </ClerkProvider>    
  );
}
