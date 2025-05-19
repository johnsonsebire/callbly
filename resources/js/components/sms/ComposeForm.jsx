import React, { useState } from 'react';
import { Label } from '../ui/label';
import { Card } from '../ui/card';
import { Button } from '../ui/button';
import { Textarea } from '../ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';

export function ComposeForm() {
  const [message, setMessage] = useState('');
  const [recipients, setRecipients] = useState('');
  const [senderId, setSenderId] = useState('');
  
  const handleSubmit = async (e) => {
    e.preventDefault();
    // TODO: Implement SMS sending logic
  };

  return (
    <Card className="p-6 max-w-4xl mx-auto">
      <form onSubmit={handleSubmit} className="space-y-6">
        <div className="space-y-2">
          <Label htmlFor="sender">Sender ID</Label>
          <Select value={senderId} onValueChange={setSenderId}>
            <SelectTrigger className="w-full">
              <SelectValue placeholder="Select a sender ID" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="register">Register a new Sender ID</SelectItem>
              {/* Sender IDs will be populated here */}
            </SelectContent>
          </Select>
          <p className="text-sm text-muted-foreground">
            You need to register a sender ID before sending SMS
          </p>
        </div>

        <div className="space-y-2">
          <Label htmlFor="message">Message</Label>
          <Textarea
            id="message"
            value={message}
            onChange={(e) => setMessage(e.target.value)}
            placeholder="Type your message here..."
            className="min-h-[100px]"
          />
          <div className="flex justify-between text-sm text-muted-foreground">
            <span>Characters: {message.length}</span>
            <span>Messages: {Math.ceil(message.length / 160)}</span>
          </div>
        </div>

        <div className="space-y-2">
          <Label htmlFor="recipients">Recipients</Label>
          <Textarea
            id="recipients"
            value={recipients}
            onChange={(e) => setRecipients(e.target.value)}
            placeholder="Enter phone numbers separated by commas, new lines, or spaces..."
            className="min-h-[100px]"
          />
          <p className="text-sm text-muted-foreground">
            Example formats: +233244123456, +233244123457
          </p>
        </div>

        <div className="flex items-center justify-between">
          <div className="text-sm text-muted-foreground">
            <span className="font-semibold">Credits required:</span> {Math.ceil(message.length / 160) * (recipients.split(/[,\s]+/).filter(Boolean).length)}
          </div>
          <Button type="submit" size="lg">
            Send Message
          </Button>
        </div>
      </form>
    </Card>
  );
}