'
' Retrieves the registry value in the provided section and at the specified key
'
Function GetRegVal(name) As Dynamic
    section = "Settings"
    sec = CreateObject("roRegistrySection", section)
     if sec.Exists(name)  
         return sec.Read(name)
     endif
     return invalid
End Function

'
' Saves a value to the registry
' @param string name - the name of the variable to be saved in the registry
' @param string value - the value to save into the registry
'
Function SetRegVal(name as String, value as String) As Void
    section = "Settings" 
    sec = CreateObject("roRegistrySection", section)
    sec.Write(name, value)
    sec.Flush()
End Function

'
' Performs a network request, returning the json result as an object
' @param string sUrl - the url to request
' @return object - the object created from the result json.
'
Function GetJSON(sUrl as String) as Object
    searchRequest = CreateObject("roUrlTransfer") 
    searchRequest.SetURL(sUrl)
    result = ParseJson(searchRequest.GetToString())
    return result
End Function

Sub ShowMessage(messageTitle as String, message as String)
    port = CreateObject("roMessagePort")
    dialog = CreateObject("roMessageDialog")
    dialog.SetMessagePort(port) 
    dialog.SetTitle(messageTitle)
    dialog.SetText(message)
 
    dialog.AddButton(1, "Ok")
    dialog.EnableBackButton(true)
    dialog.Show()
    While True
        dlgMsg = wait(0, dialog.GetMessagePort())
        If type(dlgMsg) = "roMessageDialogEvent"
            if dlgMsg.isButtonPressed()
                if dlgMsg.GetIndex() = 1
                    exit while
                End If
            Else If dlgMsg.isScreenClosed()
                exit while
            End If
        End If
    End While
End Sub

'
' Prompts the user for a yes/no answer, returns the result
' @return boolean - true if user selects yes, false if user selects no.
Function Confirm(message, yesText as String, noText as String) as Boolean
    print "Confirming: '" + message + "', '" + yesText + "', " + noText + "'"
    port = CreateObject("roMessagePort")
    dialog = CreateObject("roMessageDialog")
    dialog.SetMessagePort(port) 
    'dialog.SetTitle(messageTitle)
    dialog.SetText(message)
 
    dialog.AddButton(1, yesText)
    dialog.AddButton(0, noText)
    dialog.EnableBackButton(true)
    dialog.Show()
    While True
        dlgMsg = wait(0, dialog.GetMessagePort())
        If type(dlgMsg) = "roMessageDialogEvent"
            if dlgMsg.isButtonPressed()
                If dlgMsg.GetIndex() = 0
                    Return False
                End If
                If dlgMsg.GetIndex() = 1
                    Return True
                End If
            Else If dlgMsg.isScreenClosed()
                exit while
            End If
        End If
    End While
    'default to return false
    Return false
End Function

'
' Generates a string containing the hours minutes all together for presentation purposes
' @return string - a string with the hours, minutes and seconds in presentation format
'
Function GetHourMinuteSecondString(pSeconds) As String
    'convert the parameter into an integer
    pSeconds = Int(pSeconds)
    'get the number of hours, minutes and seconds
    hours = Int(pSeconds / 3600)
    minutes = Int((pSeconds / 60) mod 60)
    seconds = pSeconds mod 60
    
    resultString = ""
    'Add the hours, if there are any
    If hours > 0 Then
        resultString = hours.ToStr() + " hours "
    End If
    'Add the minutes, if there are any
    If minutes > 0 Then
        resultString = resultString + minutes.ToStr() + " minutes "
    End If
    'add the seconds, if there are any
    If seconds > 0 Then
        resultString = resultString + seconds.ToStr() + " seconds"        
    End If
    return resultString.Trim()
End Function